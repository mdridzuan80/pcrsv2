<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $appDbSchema = 'att2000_devel.dbo.';
    public $timestamps = false;
}
