<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XtraAnggota extends Model
{
    protected $table = 'xtra_userinfo';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['basedept_id'];

    public function baseDepartment()
    {
        return $this->belongsTo(Department::class, 'basedept_id');
    }

    public function flowBaseDepartment()
    {
        return $this->hasOne(FlowBahagian::class, 'dept_id', 'basedept_id');
    }
}
