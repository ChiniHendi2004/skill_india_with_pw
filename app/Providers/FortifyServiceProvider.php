<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // 1. Set login view
        Fortify::loginView(function () {
            return request()->is('admin/*')
                ? view('auth.admin-login')
                : view('auth.student-login');
        });

        // 2. Handle authentication logic
        Fortify::authenticateUsing(function (Request $request) {
            if (request()->is('admin/*')) {
                $user = DB::table('users')
                    ->where('email', $request->email)
                    ->where('role', 'admin')
                    ->first();
            } else {
                $student = DB::table('students')
                    ->where('unique_sid', $request->unique_sid)
                    ->first();

                if (!$student) return null;

                $user = DB::table('users')
                    ->where('id', $student->user_id)
                    ->where('role', 'student')
                    ->first();
            }

            if ($user && Hash::check($request->password, $user->password)) {
                return User::find($user->id); // Use Eloquent only here
            }

            return null;
        });
    }
}
