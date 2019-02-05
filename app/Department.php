<?php

namespace App;

use Auth;
use App\Base\BaseModel;

class Department extends BaseModel
{
    public function __construct()
    {
        $this->table = $this->appDbSchema . 'DEPARTMENTS';
        $this->setDateFormat(config('pcrs.modelDateFormat'));
        $this->primaryKey = 'DEPTID';
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'DEFAULTDEPTID');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function flow()
    {
        return $this->hasOne(FlowBahagian::class, 'dept_id');
    }

    public static function senaraiDepartment()
    {
        return SELF::when(Auth::user()->username !== env('PCRS_DEFAULT_USER_ADMIN', 'admin'), function ($query) {
            $query->authorize();
        })
            ->orderBy('DEPTNAME')->get();
    }

    static public function scopeAuthorize($query)
    {
        $related = [];
        $effectedDept = Auth::user()->roles()->where('key', Auth::user()->perananSemasa()->key)->get()->map(function ($item, $key) {
            return $item->departments->map(function ($item, $key) {
                return $item->DEPTID;
            });
        })->flatten()->unique()->toArray();

        foreach ($effectedDept as $dept) {
            $related = array_merge($related, array_flatten(Utility::pcrsRelatedDepartment(Department::all(), $dept)));
        }

        $query->whereIn('DEPTID', array_merge($effectedDept, $related));
    }

    public function updateFlow($request)
    {
        $this->flow()->updateOrCreate([], ['flag' => $request->input('flag'), 'ubah_user_id' => Auth::user()->username]);
    }
}
