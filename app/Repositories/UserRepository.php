<?php

namespace AttendCheck\Repositories;

use AttendCheck\User;

class UserRepository
{
    /**
     * Various HTTP Error codes.
     * 
     * @var string
     */
    const HTTP_CONFLICT = 409;
    const HTTP_NOTFOUND = 404;

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

    public function createSingleUser($data)
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->toArray();
        }

        if ($user = User::where('username', $data['username'])->first()) {
            return $user;
        }

        return User::create(array_merge($data, ['faculty_id' => 11, 'type_id' => 4]));
    }

    public function createManyUsersFromTextfield($data)
    {
        return collect(explode("\r\n", $data))->map(function ($item) {
            $field = explode(",", str_replace('"', '', $item));

            return collect([
                'username' => $field[0],
                'title' => $field[1],
                'name' => $field[2],
                'lastname' => $field[3],
            ]);
        })->map(function ($userData) {
            return $this->createSingleUser($userData);
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

    public function getAttendanceDataForMobileApp(User $user)
    {
        $record = resolve('\AttendCheck\Services\AttendanceRecordService');

        return $user->enrollments->map(function ($course) use ($record, $user)  {
            $result = [
                'code' => $course->url(),
                'attendCount' => $record->attendanceCount($course, $user),
                'lateCount' => $record->lateCount($course, $user),
                'missingCount' => $record->missingCount($course, $user),
                'missingPercentage' => $record->missingPercentage($course, $user)
            ];

            return collect($result);
        });
    }

    /**
     * Check for user avaibility to register.
     * Return an instance of \Illuminate\Http\Response if not availible
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\AttendCheck\User
     */
    public function checkUserAvailbility(Request $request)
    {
        if (! $user = User::where('username', $request->username)->first()) {
            return response()->json(['error' => 'User not exists'], self::HTTP_NOTFOUND);
        }

        if ($user->email == $request->email) {
            return response()->json(['error' => 'Email already exists'], self::HTTP_CONFLICT);
        }

        if ($user->password != null) {
            return response()->json(['error' => 'User already active'], self::HTTP_CONFLICT);
        }

        return $user;
    }
}
