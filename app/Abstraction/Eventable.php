<?php
namespace App\Abstraction;

use Carbon\Carbon;
use App\Base\BaseModel;

abstract class Eventable extends BaseModel
{
    const FINALATT = 'final';
    const CURRENTATT = 'current';
    const CUTI = 'cuti';
    const ACARA = 'acara';

    public function scopeGetEventablesByDate($query, Carbon $tarikh)
    {
        return $query->events()->where('tarikh', $tarikh);
    }

    abstract public function scopeEvents($query);
}