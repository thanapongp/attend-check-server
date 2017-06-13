<?php

namespace AttendCheck\Http\Controllers;

use AttendCheck\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showMainPage()
    {
        $user = Auth::user();

        switch ((string) $user->type) {
            case 'admin':
            case 'fac_admin':
                return $this->showUsersListDashboard($user);
            case 'teacher':
                return $this->showTeacherDashboard();
            case 'student':
                return $this->showStudentDashboard();
        }
    }

    private function showTeacherDashboard()
    {
        return view('dashboard.main', [
            'courses' => Auth::user()->courses()->get()
        ]);
    }

    private function showUsersListDashboard(User $user)
    {
        $users = User::needReviewByAdmin()->get();
        
        return view('dashboard.userlist', compact('users'));
    }
}
