<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowBahagian extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $table = 'flow_bahagian';
    protected $fillable = ['flag', 'ubah_user_id'];
}
