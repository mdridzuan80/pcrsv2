<?php

namespace App\Services;

use App\Cuti;
use App\Anggota;
use App\Parameter;
use App\Kehadiran;
use Carbon\Carbon;
use App\Kelewatan;
use App\FinalAttendance;

class FinalAttendanceService
{
    private $statusLewat = false;

    public function tarikhTamat($tkhTamat)
    {
        if ($tkhTamat->lt(Carbon::now()))
            return $tkhTamat;

        return Carbon::now()->subDays(1);
    }

    public function janaPersonelFinalAttendance(Anggota $profil, Carbon $tkhMula, Carbon $tkhTamat, $shift)
    {
        $cuti = Cuti::whereBetween('tarikh', [$tkhMula, $tkhTamat])->get();
        $rekodKehadiran = $profil->kehadiran()->rekodByMulaTamat($tkhMula, $tkhTamat)->orderBy('CHECKTIME')->get();
        $fTarikh = clone $tkhMula;

        do {
            $this->personelFinalAttendance($profil, $fTarikh, $shift, $cuti, $rekodKehadiran);
        } while ($fTarikh->addDay()->lte($tkhTamat));
    }

    public function personelFinalAttendance(Anggota $profil, Carbon $tarikh, $shift, $cuti, $rekodKehadiran)
    {
        $preData = $this->preDataFinalAttendance($profil, $tarikh, $shift, $cuti, $rekodKehadiran);

        $this->janaFinalAttendance($profil, $preData, $shift);

        if ($this->statusLewat)
        {
            $this->tambahLewat($profil, $preData, $shift, Kelewatan::FLAG_NON_SMS);
            $this->statusLewat = false;
        }
    }

    private function preDataFinalAttendance(Anggota $profil, Carbon $tarikh, $shift, $cuti, $rekodKehadiran)
    {
        return (object)[
            'anggota_id' => $profil->USERID,
            'tarikh' => $tarikh,
            'check_in' => $check_in = $this->punch($rekodKehadiran, $tarikh, $cuti, Kehadiran::PUNCH_IN, $profil->ZIP),
            'check_out' => $check_out = $this->punch($rekodKehadiran, $tarikh, $cuti, Kehadiran::PUNCH_OUT, $profil->ZIP),
            'check_in_mid' => $check_min = $this->punch($rekodKehadiran, $tarikh, $cuti, Kehadiran::PUNCH_MIN, $profil->ZIP),
            'check_out_mid' => $check_mout = $this->punch($rekodKehadiran, $tarikh, $cuti, Kehadiran::PUNCH_MOUT, $profil->ZIP),
            'tatatertib_flag' => $this->getFlag($profil, $tarikh, $check_in, $check_out, $check_min, $check_mout, $cuti, $shift),
            'shift_id' => $shift->id,
        ];
    }

    private function punch($rekodKehadiran, Carbon $tarikh, $cuti, $jnsPunch, $jnsUser)
    {
        $closureFilter = function ($value, $key) use ($tarikh, $cuti, $jnsPunch, $jnsUser) {
            switch ($jnsPunch)
            {
                case Kehadiran::PUNCH_IN:
                    if ($this->isCuti($tarikh, $cuti))
                    {
                        return $value->CHECKTIME->gte($tarikh->copy()->addHours(4)) &&
                            $value->CHECKTIME->lt($tarikh->copy()->addDays(1)->addHours(4)) &&
                            $value->CHECKTYPE != '1' && $value->CHECKTYPE != 'i';
                    }
                    else
                    {
                        return $value->CHECKTIME->gte($tarikh->copy()->addHours(4)) &&
                            $value->CHECKTIME->lt($tarikh->copy()->addHours(13)) &&
                            $value->CHECKTYPE != '1' && $value->CHECKTYPE != 'i';
                    }

                    break;

                case Kehadiran::PUNCH_OUT:
                    if ($this->isCuti($tarikh, $cuti))
                    {
                        return $value->CHECKTIME->gte($tarikh->copy()->addHours(4)) &&
                            $value->CHECKTIME->lt($tarikh->copy()->addDays(1)->addHours(4)) &&
                            $value->CHECKTYPE != '1' && $value->CHECKTYPE != 'i';
                    }
                    else
                    {
                        return $value->CHECKTIME->gte($tarikh->copy()->addHours(13)) &&
                            $value->CHECKTIME->lt($tarikh->copy()->addDays(1)->addHours(4)) &&
                            $value->CHECKTYPE != '1' && $value->CHECKTYPE != 'i';
                    }

                    break;

                case Kehadiran::PUNCH_MIN:
                    if ($jnsUser)
                    {
                        return $value->CHECKTIME->gte($tarikh->copy()->addHours(4)) &&
                            $value->CHECKTIME->lt($tarikh->copy()->addDays(1)->addHours(4)) &&
                            $value->CHECKTYPE == '1';
                    }

                    break;

                case Kehadiran::PUNCH_MOUT:
                    if ($jnsUser)
                    {
                        return $value->CHECKTIME->gte($tarikh->copy()->addHours(4)) &&
                            $value->CHECKTIME->lt($tarikh->copy()->addDays(1)->addHours(4)) &&
                            $value->CHECKTYPE == 'i';
                    }

                    break;
            }
        };

        $data = $rekodKehadiran->filter($closureFilter);

        if ($data->isNotEmpty())
        {
            if ($jnsPunch == Kehadiran::PUNCH_IN || $jnsPunch == Kehadiran::PUNCH_MIN)
            {
                return $data->first()->CHECKTIME;
            }

            if ($jnsPunch == Kehadiran::PUNCH_OUT || $jnsPunch == Kehadiran::PUNCH_MOUT)
            {
                return $data->last()->CHECKTIME;
            }
        }

        return null;
    }

    public function getFlag($profil, $tarikh, $checkIn, $checkOut, $checkMin, $checkMout, $cuti, $shift)
    {
        if (! $this->isCuti($tarikh, $cuti))
        {
            if ($profil->ZIP) {
                if ((is_null($checkIn) || is_null($checkOut)) && (is_null($checkMin) || is_null($checkMout)) && $this->isLate($checkIn, $shift))
                {
                    return Kehadiran::FLAG_TATATERTIB_TUNJUK_SEBAB;
                }
            }
            else
            {
                if (is_null($checkIn) || $this->isLate($checkIn, $shift) || is_null($checkOut))
                {
                    return Kehadiran::FLAG_TATATERTIB_TUNJUK_SEBAB;
                }
            }
        }

        return Kehadiran::FLAG_TATATERTIB_CLEAR;
    }

    public function isCuti($tarikh, $cuti)
    {
        return $cuti->contains(function ($item, $key) use ($tarikh) {
            return $item->tarikh->eq($tarikh);
        }) ||
            $tarikh->dayOfWeek == Carbon::SATURDAY ||
            $tarikh->dayOfWeek == Carbon::SUNDAY;
    }

    public function isLate($check_in, $shift)
    {
        if (! $check_in)
        {
            return $this->statusLewat = false;
        }

        $rulePunchIn = Carbon::parse($check_in->toDateString() . " " . $shift->check_in->toTimeString()) ;
        $paramBenarLewat = (int) Parameter::where('kod', 'P_BENAR_LEWAT')->first()->nilai;

        return $this->statusLewat = $check_in->gte($rulePunchIn->addMinutes($paramBenarLewat));
    }

    public function janaFinalAttendance($profil, $preData, $shift)
    {
        FinalAttendance::updateOrCreate(['anggota_id' => $preData->anggota_id, 'tarikh' => $preData->tarikh], (array) $preData);
    }

    public function tambahLewat($profil, $preData, $shift, $smsFlag)
    {
        $lewat = new Kelewatan;
        $lewat->anggota_id = $profil->USERID;
        $lewat->shift_id = $shift->id;
        $lewat->check_in = $preData->check_in;
        $lewat->send_sms_flag = $smsFlag;
        
        return $lewat->save();
    }

    public function hapusLewat($profil, $tkhMula, $tkhTamat)
    {
        return Kelewatan::where('anggota_id', $profil->USERID)
            ->where('check_in', '>=', $tkhMula)
            ->where('check_in', '<=', $tkhTamat)
            ->delete();
    }
}