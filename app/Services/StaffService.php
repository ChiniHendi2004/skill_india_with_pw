<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StaffService
{
    public function getStaffByType($empType, $tenantId)
    {
        $staff = DB::table('user_profile')
            ->join('designation_master', 'user_profile.designation', '=', 'designation_master.designation_id')
            ->where('user_profile.emp_type', $empType)
            ->where('user_profile.tenant_id', $tenantId)
            ->select(
                'user_profile.id',
                'user_profile.name',
                'designation_master.designation_type as designation',
                'user_profile.image'
            )
            ->get();

        return $staff->map(function ($staff) {
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'designation' => $staff->designation ?? 'Unknown',
                'image' => asset($staff->image),
            ];
        });
    }
}

