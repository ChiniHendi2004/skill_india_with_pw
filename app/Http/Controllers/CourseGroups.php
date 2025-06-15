<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseGroups extends Controller
{
    public function createCourseGroupPage()
    {
        return view('BackendPages.Course.createcoursegroup');
    }

    public function courseGroupList()
    {
        return view('BackendPages.Course.coursegrouplistpage');
    }



    public function store(Request $request)
    {
        $id = DB::table('course_groups')->insertGetId([
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json([
            'message' => 'Course Group Created Succesfully',
            'id' => $id
        ], 200);
    }

    public function list()
    {
        return DB::table('course_groups')->get();
    }


    public function activeList()
    {
        return DB::table('course_groups')->where('status', '1')->get();
    }



    public function select($id)
    {
        return DB::table('course_groups')->where('id', $id)->first();
    }


    public function update(Request $request, $id)
    {
        DB::table('course_groups')->where('id', $id)->update([
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Updated successfully']);
    }

    
    public function destroy($id)
    {
        DB::table('course_groups')->where('id', $id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }


    public function statusChange(Request $request, $id)
    {
        DB::table('course_groups')->where('id', $id)->update([
            'status' => $request->status
        ]);

        return response()->json(['message' => 'Status Chnaged successfully']);
    }
}
