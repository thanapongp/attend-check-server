<?php

namespace AttendCheck\Http\Controllers;

use AttendCheck\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showMainPage(Request $request)
    {
        $user = $request->user();

        switch ($user->type) {
            case 'admin':
            case 'fac_admin':
                return $this->showUsersListDashboard($user);
            case 'student':
                return $this->showStudentDashboard($user);
            case 'teacher':
                return $this->showTeacherDashboard($user);
        }
    }

    private function showTeacherDashboard(User $user)
    {
        $courses = $user->courses()->get();
        
        return view('dashboard.main', compact('courses'));
    }

    private function showUsersListDashboard(User $user)
    {
        $users = $user->isAdmin()
                 ? User::needReviewByAdmin()->get()
                 : User::needReviewByFacAdmin()->get();
        
        return view('dashboard.userlist', compact('users'));
    }

    private function showStudentDashboard($user)
    {
        return view('dashboard.user.studentProfile', compact('user'));
    }
}
