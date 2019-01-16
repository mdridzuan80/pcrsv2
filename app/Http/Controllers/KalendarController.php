<?php

namespace App\Http\Controllers;

use App\Cuti;
use App\Acara;
use App\Anggota;
use App\Kehadiran;
use App\FinalAttendance;
use League\Fractal\Manager;
use App\Transformers\Event;
use App\Base\BaseController;
use Illuminate\Http\Request;
use App\Abstraction\Eventable;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use App\Http\Requests\StoreAcaraRequest;

class KalendarController extends BaseController
{
    private $_eventable;
    private $_viewAcara;

    public function __construct(FinalAttendance $final, Cuti $cuti, Acara $acara)
    {
        $this->_eventable = [
            Eventable::FINALATT => $final,
            Eventable::CUTI => $cuti,
            Eventable::ACARA => $acara,
        ];
    }

    public function rpcEventAnggotaIndex(Anggota $profil, Request $request, Manager $fractal, Event $event)
    {
        $checkinout = collect($profil->finalKehadiran()->events()->whereBetween('tarikh', [$request->input('start'), $request->input('end')])->get()->toArray());
        $cuti = Cuti::events()->whereBetween('tarikh', [$request->input('start'), $request->input('end')])->get()->toArray();

        $acaraMula = $profil->acara()->events()->whereBetween('masa_mula', [$request->input('start'), $request->input('end')]);
        $acaraTamat = $profil->acara()->events()->whereBetween('masa_tamat', [$request->input('start'), $request->input('end')])->union($acaraMula)->get();

        $events = $checkinout->merge($cuti)->merge($acaraTamat);

        if ($checkIn = optional(Kehadiran::events()->whereBetween('CHECKTIME', [today()->addHours(4), today()->addHours(13)])->first())->toArray())
            $events = $events->push($checkIn);
        else
            $events->push(Kehadiran::itemEventableNone());

        $resource = new Collection($events, $event);
        $transform = $fractal->createData($resource);

        return response()->json($transform->toArray());
    }

    public function rpcEventAnggotaCreate()
    {
        return view('dashboard.acara.create');
    }

    public function rpcEventAnggotaStore(Anggota $profil, StoreAcaraRequest $request, Manager $fractal, Event $event)
    {
        return response()->json($fractal->createData(new Item(Acara::storeAcara($profil, $request)->eventableItem(), $event))->toArray());
    }

    public function rpcEventAnggotaShow(Anggota $profil, $acaraId, $jenisSumber)
    {
        if ($jenisSumber !== Eventable::CURRENTATT)
            $event = $this->_eventable[$jenisSumber]::events()->findOrFail($acaraId);

        if ($jenisSumber === Eventable::CURRENTATT) {
            $jenisSumber = Eventable::FINALATT;
            $event = optional(Kehadiran::events()->whereBetween('CHECKTIME', [today()->addHours(4), today()->addHours(13)])->first())->toArray();
        }

        return $this->viewAcara($jenisSumber, $event);
    }

    private function viewAcara($jenisSumber, $event)
    {
        $this->_viewAcara = [
            Eventable::FINALATT => 'dashboard.acara.show.final',
            Eventable::CUTI => 'dashboard.acara.show.cuti',
            Eventable::ACARA => 'dashboard.acara.show.acara',
        ];

        return view($this->_viewAcara[$jenisSumber], compact('event'));
    }
}
