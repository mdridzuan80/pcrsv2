<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowBahagian extends Model
{
    protected $table = 'flow_bahagian';

    protected $fillable = ['flag', 'ubah_user_id'];

    public function __construct()
    {
        $this->setDateFormat(config('pcrs.modelDateFormat'));
    }
}
