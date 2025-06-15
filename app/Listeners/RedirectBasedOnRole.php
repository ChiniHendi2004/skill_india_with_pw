<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;

class RedirectBasedOnRole
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        if ($user->role === 'admin') {
            Session::put('intended_url', '/admin/dashboard');
        } elseif ($user->role === 'student') {
            Session::put('intended_url', '/student/enrolled/courses');
        } else {
            Session::put('intended_url', '/');
        }
    }
}
