<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseDetailsController extends Controller
{
    public function addCourseDetailsPage($id)
    {
        $course = DB::table('courses')->where('id', $id)->first();
        $groups = DB::table('groups')->get();

        return view('Backendpages.Course.AddCourseDetails', [
            'id' => $id,
            'course' => $course,
            'groups' => $groups
        ]);
    }


    public function getGroupDetails($g_id)
    {
        $details = DB::table('group_details')->where('g_id', $g_id)->where('status', '1')->get();
        return response()->json($details);
    }


     public function filter(Request $request)
    {
        $query = DB::table('courses')
            ->select('courses.id', 'courses.name', 'courses.image', 'courses.description')
            ->where('courses.status', '1');

        $hasFilters = $request->has('filters') && !empty($request->input('filters'));
        $hasSearch = $request->filled('search');

        if ($hasFilters) {
            $query->join('group_master', 'courses.id', '=', 'group_master.c_id')
                ->join('group_details', 'group_master.gd_id', '=', 'group_details.id')
                ->distinct();

            $filters = $request->input('filters');
            $allSelectedDetailIds = collect($filters)->flatten()->toArray();

            if (!empty($allSelectedDetailIds)) {
                $query->whereIn('group_master.gd_id', $allSelectedDetailIds)
                    ->groupBy('courses.id', 'courses.name', 'courses.image', 'courses.description')
                    ->havingRaw('COUNT(DISTINCT group_master.g_id) = ?', [count($filters)]);
            }
        }

        if ($hasSearch) {
            $search = $request->input('search');
            $query->where('courses.name', 'like', '%' . $search . '%');
        }

        $courses = $query->get();
        return response()->json($courses);
    }


    // public function addDetails(Request $request)
    // {
    //     $c_id = $request->c_id;

    //     // Validate group selection
    //     if (!$request->has('selectedGroups') || empty($request->selectedGroups)) {
    //         return response()->json(['error' => 'No groups selected or invalid format.'], 400);
    //     }

    //     // Convert durations to total minutes
    //     $totalMinutes = (
    //         ($request->years * 525600) +
    //         ($request->months * 43800) +
    //         ($request->days * 1440) +
    //         ($request->hours * 60) +
    //         $request->minutes
    //     );

    //     // Check if course_details exists
    //     $existingDetails = DB::table('course_details')->where('c_id', $c_id)->first();

    //     if ($existingDetails) {
    //         DB::table('course_details')
    //             ->where('c_id', $c_id)
    //             ->update([
    //                 'image' => $request->image,
    //                 'description' => $request->description,
    //                 'duration' => $totalMinutes,
    //                 'updated_at' => now()
    //             ]);
    //     } else {
    //         DB::table('course_details')->insert([
    //             'c_id' => $c_id,
    //             'image' => $request->image,
    //             'description' => $request->description,
    //             'duration' => $totalMinutes,
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);
    //     }

    //     // Delete existing group_master entries
    //     DB::table('group_master')->where('c_id', $c_id)->delete();

    //     // Insert new group_master entries
    //     foreach ($request->selectedGroups as $group) {
    //         foreach ($group['details'] as $gd_id) {
    //             DB::table('group_master')->insert([
    //                 'c_id' => $c_id,
    //                 'g_id' => $group['g_id'],
    //                 'gd_id' => $gd_id,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Course details and groups added/updated successfully.']);
    // }


    public function addDetails(Request $request)
    {
        $c_id = $request->c_id;

        // Parse and decode selectedGroupDetails (accept both JSON string or array)
        $selectedGroups = $request->input('selectedGroupDetails');

        if (is_string($selectedGroups)) {
            $selectedGroups = json_decode($selectedGroups, true);
        }

        if (empty($selectedGroups) || !is_array($selectedGroups)) {
            return response()->json(['error' => 'No groups selected or invalid format.'], 400);
        }

        // Validate c_id and other required fields (optional, you can add more validations)
        if (!$c_id || !is_numeric($c_id)) {
            return response()->json(['error' => 'Invalid course ID.'], 400);
        }

        // Convert durations to total minutes, safely handle missing inputs by defaulting to 0
        $totalMinutes = (
            (int) ($request->years ?? 0) * 525600 +
            (int) ($request->months ?? 0) * 43800 +
            (int) ($request->days ?? 0) * 1440 +
            (int) ($request->hours ?? 0) * 60 +
            (int) ($request->minutes ?? 0)
        );

        // Update or insert course_details
        $existingDetails = DB::table('course_details')->where('c_id', $c_id)->first();

        if ($existingDetails) {
            DB::table('course_details')
                ->where('c_id', $c_id)
                ->update([
                    'fee' => $request->fee,
                    'description' => $request->description,
                    'duration' => $totalMinutes,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('course_details')->insert([
                'c_id' => $c_id,
                'fee' => $request->fee,
                'description' => $request->description,
                'duration' => $totalMinutes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Delete existing group_master entries for this course
        DB::table('group_master')->where('c_id', $c_id)->delete();

        // Insert new group_master entries from selectedGroups array
        foreach ($selectedGroups as $group) {
            // Validate expected keys before inserting
            if (!isset($group['g_id'], $group['details']) || !is_array($group['details'])) {
                continue; // skip invalid entries
            }

            foreach ($group['details'] as $gd_id) {
                DB::table('group_master')->insert([
                    'c_id' => $c_id,
                    'g_id' => $group['g_id'],
                    'gd_id' => $gd_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Course details and groups added/updated successfully.']);
    }
}
