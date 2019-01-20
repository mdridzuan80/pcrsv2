<?php

namespace App;

use App\Base\BaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Anggota extends BaseModel
{
    public function __construct()
    {
        $this->table = $this->appDbSchema . 'USERINFO';
        $this->primaryKey = 'USERID';
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'DEFAULTDEPTID');
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'anggota_shift', 'anggota_id', 'shift_id')
            ->as('waktu_bekerja_anggota')
            ->withPivot('id', 'tkh_mula', 'tkh_tamat');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'USERID', 'USERID');
    }

    public function finalKehadiran()
    {
        return $this->hasMany(FinalAttendance::class, 'anggota_id');
    }

    public function penilai()
    {
        return $this->hasOne(Anggota::class, 'SSN', 'OPHONE');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'anggota_id');
    }

    public function acara()
    {
        return $this->hasMany(Acara::class, 'anggota_id');
    }

    public function pegawaiPenilai()
    {
        return $this->hasOne(PegawaiPenilai::class, 'anggota_id');
    }

    public function pegawaiYangDinilai()
    {
        return $this->hasMany(PegawaiPenilai::class, 'pegawai_id');
    }

    public function pegawaiYangMenilai()
    {
        return $this->hasMany(PegawaiPenilai::class, 'anggota_id');
    }

    public function scopeAuthorize($query)
    {
        $related = [];
        $effectedDept = Auth::user()->roles()->where('key', Auth::user()->perananSemasa()->key)->get()->map(function ($item, $key) {
            return $item->departments->map(function ($item, $key) {
                return $item->DEPTID;
            });
        })->flatten()->unique()->toArray();

        foreach ($effectedDept as $dept) {
            $related = array_merge($related, array_flatten(Utility::pcrsRelatedDepartment(Department::all(), $dept)));
        }

        $query->whereIn('DEFAULTDEPTID', array_merge($effectedDept, $related));
    }

    public function scopeSenaraiAnggota($query, $search)
    {
        $query->with('user')
            ->selectRaw('ROW_NUMBER() OVER (ORDER BY convert(bigint, Badgenumber)) AS numrow, *')
            ->whereRaw('DEFAULTDEPTID IN(' . $search->get('dept') . ')')
            ->when($search->get('key'), function ($query) use ($search) {
                $query->whereRaw("Badgenumber+Name+SSN+TITLE LIKE '%" . $search->get('key') . "%'");
            })
            ->when(Auth::user()->username !== env('PCRS_DEFAULT_USER_ADMIN', 'admin'), function ($query) {
                $query->authorize();
            });
    }

    public function kemaskiniProfil(Request $request)
    {
        $this->Name = $request->input('txtNama');
        $this->SSN = $request->input('txtNoKP');
        $this->TITLE = $request->input('txtJawatan');
        $this->street = $request->input('txtEmail');
        $this->PAGER = $request->input('txtTelefon');
        $this->DEFAULTDEPTID = $request->input('txtDepartmentId');
        $this->ZIP = $request->input('comTrack');

        $this->save();
    }

    public function kemaskiniPPP(Request $request)
    {
        $this->pegawaiYangMenilai()->updateOrCreate(
            [
                'pegawai_flag' => $request->input('pegawai-flag'),
            ],
            [
                'pegawai_id' => $request->input('comSenPPP'),
            ]
        );
    }
}
