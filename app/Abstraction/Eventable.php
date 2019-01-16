<?php
namespace App\Abstraction;

use App\Base\BaseModel;

abstract class Eventable extends BaseModel
{
    const FINALATT = 'final';
    const CURRENTATT = 'current';
    const CUTI = 'cuti';
    const ACARA = 'acara';

    abstract public function scopeEvents($query);
}