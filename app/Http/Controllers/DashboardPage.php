<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardPage extends Controller
{

    public function dashboardPage()
    {
        return view('Backendpages.Dashboard.dashboard');
    }

    public function adminRegisterPage()
    {
        return view('auth.register');
    }
    public function adminLoginPage()
    {
        return view('auth.login');
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        DB::table('users')->insert([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // No student profile is created for admin

        return response()->json(['message' => 'Admin registered successfully']);
    }


    public function loginAdmin(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to find the admin user by email
        $admin = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        // Verify the password
        if (!Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Save admin ID in session
        session(['admin_id' => $admin->id]);

        return response()->json([
            'success' => true,
            'message' => 'Admin logged in successfully',
            'admin_id' => $admin->id // Optional: if you're using it
        ]);
    }
}
