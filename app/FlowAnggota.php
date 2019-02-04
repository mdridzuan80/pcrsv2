<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowAnggota extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $table = 'flow_anggota';
    protected $fillable = ['flag', 'ubah_user_id'];

}
