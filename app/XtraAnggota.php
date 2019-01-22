<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XtraAnggota extends Model
{
    protected $table = 'xtra_userinfo';

    protected $fillable = ['basedept_id'];

    public function baseDepartment()
    {
        return $this->belongsTo(Department::class, 'basedept_id');
    }
}
