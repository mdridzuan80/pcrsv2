<?php

namespace App\Http\Controllers;

use App\Shift;
use App\Anggota;
use Carbon\Carbon;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Base\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Transformers\WaktuBekerjaTransformer;
use League\Fractal\Resource\Collection as FCollection;
use App\Facades\FinalAttendanceFacade as FinalAttendance;


class WaktuBerperingkatController extends BaseController
{
    private function excludeCreatedBulanan(Anggota $profil, Collection $bulan, $tahun)
    {
        $convert = function($array_data) {
            $d = [];

            foreach($array_data as $data)
            {
                $d[] = (array) $data;
            }

            return collect(array_flatten($d));
        };

        return $bulan->diff(
            $convert($profil->shifts()->newPivotStatement()
            ->selectRaw('MONTH(tkh_mula) as bulan')
            ->whereRaw('YEAR(tkh_mula) = ?', [$tahun])
            ->where('anggota_id', $profil->USERID)
            ->get()->toArray())
        );
    }

    private function hasCreateHarian(Anggota $profil, Carbon $tkhMula, Carbon $tkhTamat)
    {
        return $profil->shifts()->newPivotStatement()
            ->where('anggota_id', $profil->USERID)
            ->whereRaw('(tkh_mula >= ? AND tkh_tamat <= ?) OR (tkh_mula <= ? AND tkh_tamat >= ?)', [$tkhMula, $tkhTamat, $tkhTamat, $tkhMula])
            ->exists();
    }

    public function rpcIndex(Anggota $profil)
    {
        $shifts = Shift::all();
        return view('anggota.waktu_bekerja.index', compact('shifts', 'profil'));
    }

    public function rpcBulanan(Manager $fractal, WaktuBekerjaTransformer $WaktuBekerjaTransformer, Anggota $profil, $tahun)
    {

        $shifts = $profil->shifts()
            ->whereYear('anggota_shift.tkh_mula', $tahun)
            ->orderBy('anggota_shift.tkh_mula')
            ->get();

        $resource = new FCollection($shifts, $WaktuBekerjaTransformer);
        $transform = $fractal->createData($resource);

        return response()->json($transform->toArray());
    }

    public function rpcBulananCreate(Request $request, Anggota $profil)
    {
        $tahun = $request->input('comTahun');
        $CBulan = collect($request->input('comBulan'));
        $shift = Shift::find($request->input('comWbb'));

        foreach ($this->excludeCreatedBulanan($profil, $CBulan, $tahun) as $bulan)
        {
            $tkhMula = Carbon::create($tahun, $bulan, 1, 0, 0, 0);
            $tkhTamat = Carbon::create($tahun, $bulan, cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun), 0, 0, 0);

            DB::transaction(function () use ($profil, $tkhMula, $tkhTamat, $bulan, $shift) {
                $profil->shifts()->attach($shift, ['tkh_mula' => $tkhMula, 'tkh_tamat' => $tkhTamat]);

                // Jika bulan yang dipilih lebih kecil dari bulan semasa
                // jana semula final attendance
                if ($bulan <= Carbon::now()->month) {
                    FinalAttendance::janaPersonelFinalAttendance($profil, $tkhMula, FinalAttendance::tarikhTamat($tkhTamat), $shift);
                }
            });
        }
    }

    public function rpcHarianCreate(Request $request, Anggota $profil)
    {
        $tkhMula = Carbon::parse($request->input('txtTarikhMula'));
        $tkhTamat = Carbon::parse($request->input('txtTarikhTamat'));
        $shift = Shift::find($request->input('comWbb'));

        if ($this->hasCreateHarian($profil, $tkhMula, $tkhTamat))
        {
            return abort(409);
        }

        $profil->shifts()->attach($shift, ['tkh_mula' => $tkhMula, 'tkh_tamat' => $tkhTamat]);
    }

    public function rpcDelete(Anggota $profil, $waktuBekerjaId)
    {
        DB::transaction(function () use ($profil, $waktuBekerjaId) {
            $waktuBekerja = $profil->shifts()
                ->newPivotStatement()
                ->where('id', $waktuBekerjaId)
                ->where('anggota_id', $profil->USERID)
                ->first();

            $profil->shifts()->newPivotStatement()->where('id', $waktuBekerjaId)->where('anggota_id', $profil->USERID)->delete();
            FinalAttendance::hapusLewat($profil, $waktuBekerja->tkh_mula, $waktuBekerja->tkh_tamat);
        });
        
        $huhu = 'haha';
    }

    public function rpcHarian(Anggota $profil, $tahun)
    {

    }
}
