<?php

namespace App;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $dates = [
        'check_in',
        'check_out',
    ];

    public function anggota()
    {
        return $this->belongsToMany(Anggota::class, 'anggota_shift', 'shift_id', 'anggota_id')
            ->withPivot('tkh_mula', 'tkh_tamat', 'id');
    }
}
