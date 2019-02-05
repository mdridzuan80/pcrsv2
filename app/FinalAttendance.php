<?php

namespace App;

use App\Abstraction\Eventable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class FinalAttendance extends Eventable
{
    protected $fillable = [
        'anggota_id',
        'tarikh',
        'shift_id',
        'check_in',
        'check_out',
        'check_in_mid',
        'check_in_out',
        'tatatertib_flag',
    ];

    protected $dates = [
        'tarikh',
        'check_in',
        'check_out',
        'check_in_mid',
        'check_in_out',
        'start',
        'end',
    ];

    public function __construct()
    {
        $this->setDateFormat(config('pcrs.modelDateFormat'));
    }

    public function scopeEvents($query)
    {
        return $query->select(DB::raw('\'IN: \' + ISNULL(RIGHT(convert(varchar, check_in, 100),7), \'-\') + CHAR(10) + \'OUT: \' + ISNULL(RIGHT(convert(varchar, check_out, 100),7), \'-\') as \'title\''), DB::raw('tarikh as \'start\''), DB::raw('tarikh as \'end\''), DB::raw('\'true\' as \'allDay\''), DB::raw('\'#1abc9c\' as \'color\''), DB::raw('\'#000\' as \'textColor\''), DB::raw('id'), DB::raw('\'' . Eventable::FINALATT . '\' as [table_name]'));
    }

    public function eventCheckIn()
    {
        $masa = explode("\n", $this->title);
        return trim(explode(":", $masa[0], 2)[1]);
    }

    public function eventCheckOut()
    {
        $masa = explode("\n", $this->title);
        return trim(explode(":", $masa[1], 2)[1]);
    }

}
