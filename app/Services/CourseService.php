<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CourseService
{
    public function getCourses($tenantId)
    {
        return DB::table('course')
            ->where('tenant_id', $tenantId)
            ->get()
            ->toArray();
    }

    public function getCourseDetail($id, $tenantId)
    {
        $course = DB::table('course')
            ->where('tenant_id', $tenantId)
            ->where('id', $id)
            ->first();

        if (!$course) {
            return ['course' => null, 'paragraphs' => []];
        }

        $courseDetails = DB::table('course_details')
            ->where('course_id', $id)
            ->orderBy('paragraph_order', 'asc')
            ->pluck('paragraph_text')
            ->toArray();

        return [
            'course' => $course,
            'paragraphs' => $courseDetails
        ];
    }
}
