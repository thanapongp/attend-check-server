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
            case 'student':
                return $this->showStudentDashboard($user);
            case 'teacher':
                return $this->showTeacherDashboard();
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
        $users = ((string) $user->type) == 'admin'
                 ? User::needReviewByAdmin()->get()
                 : User::needReviewByFacAdmin()->get();
        
        return view('dashboard.userlist', compact('users'));
    }

    private function showStudentDashboard($user)
    {
        return view('dashboard.user.studentProfile', compact('user'));
    }
}
