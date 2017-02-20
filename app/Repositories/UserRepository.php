<?php

namespace AttendCheck\Repositories;

use AttendCheck\User;

class UserRepository
{
    public function createUsersFromApiResponse(array $users)
    {
        return collect($users)->filter(function ($user) {
            return (User::where('username', $user->STUDENTCODE)->first() == null);

        })->map(function ($user) {
            return User::create([
                'username' => $user->STUDENTCODE,
                'password' => null,
                'email' => null,
                'title' => $user->PREFIXNAME,
                'name' => $user->STUDENTNAME,
                'lastname' => $user->STUDENTSURNAME,
                'faculty_id' => $user->FACULTYID,
                'type_id' => '4',
            ]);
        });
    }
}
