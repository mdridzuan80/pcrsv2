<?php

namespace App;

use App\Department;
use Illuminate\Support\Collection;

class Utility
{
    public static function pcrsListerDepartment($SubDepartmentOption, $departmentId)
    {
        return ($SubDepartmentOption == 'true') ? implode(',', array_flatten(SELF::pcrsRelatedDepartment(Department::all(), $departmentId))) : $departmentId;
    }

    public static function pcrsRelatedDepartment(Collection $elements, $parentId = 1)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ((int) $element->SUPDEPTID === (int) $parentId) {
                $branch[] = $element->DEPTID;
                $c = SELF::pcrsRelatedDepartment($elements, $element->DEPTID);
                if ($c) {
                    $branch[] = $c;
                }
            }
        }
        
        array_push($branch, $parentId);

        return $branch;
    }

    public static function PcrsbuildTreeRelated($departments, $parentId = 1, $parentIncId = 0, $branch = [])
    {
        foreach ($departments as $department) {
            $data[] = [
                'id' => $department->DEPTID,
                'parent' => ($department->SUPDEPTID ? $department->SUPDEPTID : '#'),
                'text' => $department->DEPTNAME,
                'state' => [
                    'opened' => ($department->DEPTID == $parentId) ? true : false,
                    'selected' => ($department->DEPTID == $parentId) ? true : false
                ]
            ];
        }

        return $data;
    }
}