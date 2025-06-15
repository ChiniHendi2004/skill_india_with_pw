<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class StudentController extends Controller
{
    public function studentRegisterPage()
    {
        return view('Studentscreen.registration');
    }
    public function studentLoginPage()
    {
        return view('Studentscreen.login');
    }
    public function forgetIdPage()
    {
        return view('Studentscreen.forgetID');
    }

    public function viewEnrolledCoursesPage()
    {
        return view('Studentscreen.courseenrolledpage'); // HTML page, not Blade layout
    }


    public function courseapplyPage($id)
    {
        return view('Studentscreen.courseapply', ['id' => $id]);
    }


    public function getGroups()
    {
        $groups = DB::table('groups')->select('id', 'name')->get();

        foreach ($groups as $group) {
            $group->group_details = DB::table('group_details')
                ->where('g_id', $group->id)
                ->get();
        }

        return response()->json($groups);
    }




    public function registerStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|max:10',
            'mobile' => 'required|numeric',
            'email' => 'required|email|unique:students',
            'address' => 'required|string',
            'aadhar_no' => 'required|numeric',
            'qualification' => 'required|string',
            'password' => 'required|min:6|confirmed', // Add this
        ]);

        // Upload Aadhar PDF
        if ($request->hasFile('aadhar_pdf')) {
            $aadharPdf = $request->file('aadhar_pdf');
            $aadharPdfPath = $aadharPdf->store('aadhar_pdfs', 'public');
        }

        // Generate unique ID
        $uniqueId = strtoupper(uniqid('STU'));

        // Create user in users table
        $userId = DB::table('users')->insertGetId([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create student profile
        DB::table('students')->insert([
            'user_id' => $userId,
            'unique_sid' => $uniqueId,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'aadhar_no' => $request->aadhar_no,
            'qualification' => $request->qualification,
            'aadhar_pdf' => $aadharPdfPath,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Registration successful! Your Unique ID is ' . $uniqueId
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'unique_sid' => 'required',
            'password' => 'required'
        ]);

        $student = DB::table('students')->where('unique_sid', $request->unique_sid)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Unique ID'
            ]);
        }

        $user = DB::table('users')->where('id', $student->user_id)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password'
            ]);
        }

        // ✅ Store student_id in Laravel session
        $request->session()->put('student_id', $student->id);

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'student_id' => $student->id
        ]);
    }


    public function findUniqueId(Request $request)
    {
        $student = DB::table('students')
            ->where('aadhar_no', $request->aadhar_no)
            ->first();

        if ($student) {
            return response()->json(['unique_sid' => $student->unique_sid], 200);
        } else {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }




    public function index()
    {
        // Fetch groups and their details
        $groups = DB::table('groups')
            ->select('id', 'name')
            ->get();

        foreach ($groups as $group) {
            $group->group_details = DB::table('group_details')
                ->where('g_id', $group->id)
                ->get();
        }

        // Fetch all courses initially
        $courses = DB::table('courses')->get();

        return view('Studentscreen.courseslist', compact('groups', 'courses'));
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

    public function getEnrolledCourses(Request $request)
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return response()->json(['success' => false, 'message' => 'Student not logged in.']);
        }

        $courses = DB::table('course_applications')
            ->join('courses', 'course_applications.course_id', '=', 'courses.id')
            ->join('course_details', 'courses.id', '=', 'course_details.c_id')
            ->where('course_applications.student_id', $studentId)
            ->select(
                'courses.name',
                'courses.image',
                'course_details.fee',
                'course_details.duration',
                'course_applications.status',
                'course_applications.created_at'
            )
            ->get();

        // Format duration for each course
        $courses->transform(function ($course) {
            $course->formatted_duration = $this->formatDuration($course->duration);
            return $course;
        });

        return response()->json(['success' => true, 'courses' => $courses]);
    }



    public function enroll(Request $request)
    {
        // Validate input
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        // Get student ID from session
        $studentId = session('student_id');

        if (!$studentId) {
            return response()->json(['message' => 'Student not logged in.'], 401);
        }

        // Check if already applied
        $alreadyApplied = DB::table('course_applications')
            ->where('student_id', $studentId)
            ->where('course_id', $request->course_id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json(['message' => 'Already applied for this course.'], 409);
        }

        // Insert new application
        DB::table('course_applications')->insert([
            'student_id' => $studentId,
            'course_id' => $request->course_id,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Enrollment successful.']);
    }
}
