<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ActivityService
{
    // Fetch all activities for a tenant
    public function getActivities($tenant_id)
    {
        return DB::table('activities')
            ->where('tenant_id', $tenant_id)
            ->get();
    }

    // Fetch activity details based on activity_id
    public function getActivityDetail($id)
    {
        // Fetch activity details
        $activity = DB::table('activities')
        ->where('id', $id)
            ->first();

        if (!$activity) {
            return null; // Return null if activity does not exist
        }

        // Fetch ordered paragraph_texts
        $paragraphs = DB::table('activity_details')
        ->where('activity_id', $id)
            ->orderBy('paragraph_order', 'asc') // Order by paragraph_order
            ->pluck('paragraph_text')
            ->toArray(); // Fetch only paragraph_texts in order

        return [
            'activity' => $activity,
            'paragraphs' => $paragraphs, // Ordered list of paragraphs
        ];
    }

}
