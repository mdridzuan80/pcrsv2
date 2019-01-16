<?php

namespace App\Http\Controllers;

use App\Utility;
use App\Role;
use App\Department;
use App\Base\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function departmentTree()
    {
        $data = [];

        $selected = Auth()->user()->roles()->where('key', session('perananSemasa'))->first()->pivot->department_id;
        $parentDeptId = Department::find($selected)->SUPDEPTID;

        $departments = Department::senaraiDepartment();

        foreach ($departments as $department) {
            $data[] = [
                'id' => $department->DEPTID,
                'parent' => ($department->SUPDEPTID != $parentDeptId ? $department->SUPDEPTID : '#'),
                'text' => $department->DEPTNAME,
                'state' => [
                    'opened' => ($department->DEPTID == $selected) ? true : false,
                    'selected' => ($department->DEPTID == $selected) ? true : false
                ]
            ];   
        }

        return $data;
    }
}
