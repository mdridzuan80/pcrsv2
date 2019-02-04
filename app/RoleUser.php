<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    protected $table = 'role_user';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'DEPTID');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }
}
