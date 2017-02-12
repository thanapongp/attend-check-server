<?php

namespace AttendCheck\Repositories;

use AttendCheck\User;

class UserRepository
{
    public function createUsersFromApiResponse(array $users)
    {
        // convert into collection object for better use.
        $users = collect($users);

        $newUsers = collect([]);

        $users = $users->filter(function ($user) {
            return (User::where('username', $user->STUDENTCODE)->first() == null);
        })->each(function ($user) use ($newUsers) {
            $newUser = User::create([
                'username' => $user->STUDENTCODE,
                'password' => null,
                'email' => null,
                'title' => $user->PREFIXNAME,
                'name' => $user->STUDENTNAME,
                'lastname' => $user->STUDENTSURNAME,
                'faculty_id' => $user->FACULTYID,
                'type_id' => '4',
            ]);

            $newUsers->push($newUser);
        });

        return $newUsers;
    }
}
