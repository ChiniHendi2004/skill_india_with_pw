<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationsController extends Controller
{


    public function pendingApplicationsPage()
    {
        return view('Backendpages.CourseApplications.pendingapplications');
    }

    public function rejectedApplicationsPage()
    {
        return view('Backendpages.CourseApplications.rejectedapplications');
    }

    public function acceptedApplicationsPage()
    {
        return view('Backendpages.CourseApplications.accepetedapplications');
    }

    public function viewApplicationPage($id)
    {
        return view('Backendpages.CourseApplications.viewapplication', ['id' => $id]);
    }



    public function pendingApplicationsList()
    {
        $list = DB::table('course_applications')
            ->leftJoin('courses', 'courses.id', 'course_applications.course_id')
            ->leftJoin('students', 'students.id', 'course_applications.student_id')
            ->select('courses.name', 'courses.name as Course_name', 'students.*', 'course_applications.id', 'course_applications.status as Application_status','course_applications.created_at as Application_Created')
            ->where('course_applications.status', 'Pending')
            ->get();

        return response()->json(['message' => 'Success', 'data' => $list]);
    }
    
    public function viewApplications($id)
    {
        $list = DB::table('course_applications')
            ->leftJoin('courses', 'courses.id', 'course_applications.course_id')
            ->leftJoin('students', 'students.id', 'course_applications.student_id')
            ->select('courses.name', 'courses.name as Course_name', 'students.*', 'course_applications.id', 'course_applications.status as Application_status','course_applications.updated_at as Application_Updated')
            ->where('course_applications.id', $id)
            ->first();

        return response()->json(['message' => 'Success', 'data' => $list]);
    }

    public function rejectedApplicationsList()
    {
        $list = DB::table('course_applications')
            ->leftJoin('courses', 'courses.id', 'course_applications.course_id')
            ->leftJoin('students', 'students.id', 'course_applications.student_id')
            ->select('courses.id', 'courses.name as Course_name', 'students.*', 'course_applications.id', 'course_applications.status as Application_status','course_applications.updated_at as Application_Updated')
            ->where('course_applications.status', 'Rejected')->get();

        return response()->json(['message' => 'Success', 'data' => $list]);
    }

    public function approvedApplicationsList()
    {
        $list = DB::table('course_applications')
            ->leftJoin('courses', 'courses.id', 'course_applications.course_id')
            ->leftJoin('students', 'students.id', 'course_applications.student_id')
            ->select('courses.id', 'courses.name as Course_name', 'students.*', 'course_applications.id', 'course_applications.status as Application_status')
            ->where('course_applications.status', 'Approved')->get();

        return response()->json(['message' => 'Success', 'data' => $list]);
    }

    public function statusChange(Request $request, $id)
    {

        $req=$request->status;
        DB::table('course_applications')->where('id', $id)->update([
            'status' => $req
            // status="Pending,Rejected,Approved"
        ]);
        return response()->json(['message' => 'Application '. $req.' successfully']);
    }


    public function countApplication(Request $request)
    {
        $status = $request->status;
        $count = DB::table('course_applications')->where('status', $status)->count();

        return response()->json(['message' => 'Success', 'data' => $count]);
    }
}
