<?php

namespace AttendCheck\Repositories;

use AttendCheck\User;

class UserRepository
{
    public function createUsersFromApiResponse(array $users)
    {
        // First, we create the account for student that don't have an account.
        $this->createNewUsers($users);

        // And then return the complete collection of users consists of
        // users that already have an account and new users
        return collect($users)->map(function ($user) {
            return User::where('username', $user->STUDENTCODE)->first();
        });
    }

    public function createNewUsers(array $users)
    {
        collect($users)->reject(function ($user) {
            return User::where('username', $user->STUDENTCODE)->exists();
        })->each(function ($user) {
            User::create([
                'username' => $user->STUDENTCODE,
                'title' => $user->PREFIXNAME,
                'name' => $user->STUDENTNAME,
                'lastname' => $user->STUDENTSURNAME,
                'faculty_id' => $user->FACULTYID,
                'type_id' => '4',
            ]);
        });
    }

    public function getUserDataForMobileApp(User $user)
    {
        return User::with(
            'device', 
            'attendances', 
            'enrollments.schedules', 
            'enrollments.periods'
        )->find($user->id);
    }
}
