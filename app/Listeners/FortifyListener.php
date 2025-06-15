<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Fortify;
use App\Models\User;

class FortifyListener extends ServiceProvider
{
    public function boot()
    {
        // Custom login form
        Fortify::loginView(function () {
            return request()->is('admin/*')
                ? view('auth.admin-login')
                : view('auth.student-login');
        });

        // Custom login logic
        Fortify::authenticateUsing(function (Request $request) {
            if ($request->is('admin/*')) {
                $user = DB::table('users')->where('email', $request->email)->where('role', 'admin')->first();
            } else {
                $student = DB::table('students')->where('unique_sid', $request->unique_sid)->first();
                if (!$student) return null;

                $user = DB::table('users')->where('id', $student->user_id)->where('role', 'student')->first();
            }

            if ($user && Hash::check($request->password, $user->password)) {
                return User::find($user->id); // Use Eloquent only for returning the model
            }

            return null;
        });
    }
}
