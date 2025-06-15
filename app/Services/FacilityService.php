<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FacilityService
{
    // Fetch facilities based on tenant_id
    public function getFacilities($tenant_id)
    {
        return DB::table('facilities')
            ->where('tenant_id', $tenant_id) // Filter facilities by tenant_id
            ->get();
    }

    // Fetch facility details based on facility_id
    public function getFacilityDetail($id)
    {
        // Fetch the facility details
        $facility = DB::table('facilities')
            ->where('id', $id)
            ->first();

        // Fetch the paragraphs ordered by 'paragraph_order'
        $paragraphs = DB::table('facility_details')
            ->where('facility_id', $id)
            ->orderBy('paragraph_order') // Order by 'paragraph_order' in ascending order
            ->pluck('paragraph_text'); // Retrieve only the 'paragraph_text' column

        // Return the facility details along with the ordered paragraphs
        return [
            'facility' => $facility,
            'paragraphs' => $paragraphs,
        ];
    }
}
