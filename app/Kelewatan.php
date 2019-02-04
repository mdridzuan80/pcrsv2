<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kelewatan extends Model
{
    const FLAG_SMS = 0;
    const FLAG_NON_SMS = 1;

    protected $table = 'Kelewatan';
    protected $dateFormat = 'Y-m-d H:i:s';
}
