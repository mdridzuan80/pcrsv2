<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowBahagian extends Model
{
    protected $table = 'flow_bahagian';
    protected $fillable = ['flag', 'ubah_user_id'];

    const BIASA = 'BIASA';
    const KETUA = 'KETUA';
}
