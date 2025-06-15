<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DepartmentService
{
    public function getDepartments($tenantId)
    {
        return DB::table('departments')
            ->where('tenant_id', $tenantId)
            ->get()
            ->toArray();
    }

    public function getDepartmentDetail($id, $tenantId)
    {
        $department = DB::table('departments')
            ->where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();

        if (!$department) {
            return ['department' => null, 'paragraphs' => []];
        }

        $departmentDetails = DB::table('department_details')
            ->where('department_id', $id)
            ->orderBy('paragraph_order', 'asc')
            ->pluck('paragraph_text')
            ->toArray();

        return [
            'department' => $department,
            'paragraphs' => $departmentDetails
        ];
    }
}
