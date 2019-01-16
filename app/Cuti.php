<?php

namespace App;

use App\Abstraction\Eventable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Eventable
{
    protected $table = 'cuti';
    protected $dates = [
        'tarikh',
        'start',
        'end',
    ];

    public function scopeEvents($query)
    {
        return $query->select(DB::raw('perihal as [title]'), DB::raw('tarikh as [start]'), DB::raw('tarikh as [end]'), DB::raw('\'true\' as [allDay]'), DB::raw('\'#f1c40f\' as [color]'), DB::raw('\'#000\' as [textColor]'), DB::raw('id'), DB::raw('\'' . Eventable::CUTI . '\' as [table_name]'));
    }
}
