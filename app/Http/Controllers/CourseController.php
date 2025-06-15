<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{

    public function createCoursePage()
    {
        return view('Backendpages.Course.CreateCourse');
    }
    public function editCoursePage($id)
    {
        return view('Backendpages.Course.EditCourse', ['id' => $id]);
    }

    public function courseListPage()
    {
        return view('Backendpages.Course.CourseList');
    }
    public function courseEditPage($id)
    {
        return view('Backendpages.Course.EditCourse', ['id' => $id]);
    }
    public function coursViewPage($id)
    {
        return view('Backendpages.Course.ViewCourseDetails', ['id' => $id]);
    }



    public function store(Request $request)
    {
        // 1. Insert Course
        $courseId = DB::table('courses')->insertGetId([
            'cg_id' => $request->cg_id,
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now()
        ]);


        return response()->json(['success' => true, 'message' => 'Course created Succesfully', 'course_id' => $courseId]);
    }

    public function list()
    {
        $data = DB::table('courses')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }


    public function select($id)
    {
        $group = DB::table('courses')->where('id', $id)->first();

        if (!$group) {
            return response()->json(['message' => 'course not found'], 404);
        }

        return response()->json(['data' => $group]);
    }


    public function index()
    {
        $courses = DB::table('courses')
            ->join('course_groups', 'courses.cg_id', '=', 'course_groups.id')
            ->select('courses.*', 'course_groups.name as group_name')
            ->get();

        foreach ($courses as $course) {
            // Fetch variations
            $variations = DB::table('master_variations')
                ->join('variations', 'master_variations.v_id', '=', 'variations.id')
                ->join('variation_details', 'master_variations.vd_id', '=', 'variation_details.id')
                ->where('master_variations.c_id', $course->id)
                ->select(
                    'variations.name as variation_name',
                    'variation_details.value as variation_value'
                )
                ->get();

            $course->variations = $variations;

            // Fetch description from course_details
            $course->description = DB::table('course_details')
                ->where('c_id', $course->id)
                ->value('description');
        }

        return response()->json($courses);
    }



    public function getAllCourseDetails()
    {
        // Get all courses
        $courses = DB::table('courses')->get();

        // Loop through each course and get its group values
        $coursesWithGroups = $courses->map(function ($course) {
            // Get group + group_detail values for this course from group_master
            $groupDetails = DB::table('group_master')
                ->join('groups', 'group_master.g_id', '=', 'groups.id')
                ->join('group_details', 'group_master.gd_id', '=', 'group_details.id')
                ->where('group_master.c_id', $course->id)
                ->select(
                    'groups.name as group_name',
                    'group_details.value as group_value'
                )
                ->get();

            // Attach the group details to the course object
            $course->group_details = $groupDetails;
            return $course;
        });

        return response()->json($coursesWithGroups);
    }

    public function formatDuration($minutes)
    {
        $years = floor($minutes / 525600);
        $minutes %= 525600;

        $months = floor($minutes / 43200);
        $minutes %= 43200;

        $days = floor($minutes / 1440);
        $minutes %= 1440;

        $hours = floor($minutes / 60);
        $minutes %= 60;

        $parts = [];
        if ($years) $parts[] = "$years year" . ($years > 1 ? 's' : '');
        if ($months) $parts[] = "$months month" . ($months > 1 ? 's' : '');
        if ($days) $parts[] = "$days day" . ($days > 1 ? 's' : '');
        if ($hours) $parts[] = "$hours hour" . ($hours > 1 ? 's' : '');
        if ($minutes) $parts[] = "$minutes minute" . ($minutes > 1 ? 's' : '');

        return implode(', ', $parts);
    }



    public function getCompleteCourseDetails($id)
    {
        // Fetch the course basic info
        $course = DB::table('courses')->where('id', $id)->first();
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Fetch course details
        $courseDetails = DB::table('course_details')->where('c_id', $id)->first();
        if (!$courseDetails) {
            return response()->json(['message' => 'Course details not found'], 404);
        }

        // Format the duration (in minutes to readable string)
        $formattedDuration = $this->formatDuration($courseDetails->duration);

        // Fetch groups and group values this course belongs to
        $groups = DB::table('group_master as gm')
            ->join('groups as g', 'gm.g_id', '=', 'g.id')
            ->join('group_details as gd', 'gm.gd_id', '=', 'gd.id')
            ->where('gm.c_id', $id)
            ->select(
                'g.id as group_id',
                'g.name as group_name',
                'gd.id as group_detail_id',
                'gd.value as group_value'
            )
            ->get();

        // Return combined result
        return response()->json([
            'course_name' => $course->name,
            'course_image' => $course->image,
            'course_details' => $courseDetails,
            'formatted_duration' => $formattedDuration,
            'groups' => $groups
        ]);
    }


    public function update(Request $request, $id)
    {
        DB::table('courses')->where('id', $id)->update([
            'cg_id' => $request->cg_id,
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Updated successfully']);
    }


    public function destroy($id)
    {
        if (!$id || !is_numeric($id)) {
            return response()->json(['message' => 'Invalid or missing course ID'], 400);
        }

        // Check if the course exists
        $course = DB::table('courses')->where('id', $id)->first();
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Use transaction to ensure data integrity
        DB::beginTransaction();
        try {
            // Delete related records
            DB::table('course_details')->where('c_id', $id)->delete();
            DB::table('group_master')->where('c_id', $id)->delete();
            DB::table('courses')->where('id', $id)->delete();

            DB::commit();
            return response()->json(['message' => 'Course deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete course', 'error' => $e->getMessage()], 500);
        }
    }



    public function statusChange(Request $request, $id)
    {
        DB::table('courses')->where('id', $id)->update([
            'status' => $request->status

        ]);
        return response()->json(['message' => 'Status Chnaged successfully']);
    }
}
