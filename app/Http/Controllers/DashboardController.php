<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user()->loadCount(['experiences', 'educations', 'certificates', 'skills']);
        $profile = $user->profile;

        return view('dashboard', compact('user', 'profile'));
    }
}
