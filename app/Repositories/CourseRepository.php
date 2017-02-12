<?php

namespace AttendCheck\Repositories;

use Illuminate\Http\Request;
use AttendCheck\Api\Requestor;
use AttendCheck\Course\Course;
use Illuminate\Support\Facades\Auth;
use AttendCheck\Repositories\UserRepository;

class CourseRepository
{
    protected $requestor;
    protected $userRepo;

    public function __construct(Requestor $requestor, UserRepository $userRepo)
    {
        $this->requestor = $requestor;
        $this->userRepo = $userRepo;
    }

    /**
     * Query a course from API and save it to database.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \AttendCheck\Course\Course
     */
    public function create(array $data)
    {
        $user = Auth::user();

        $course = new Course([
            "code" => $data['code'],
            "name" => $data['name'],
            "section" => $data['section'],
            "semester" => $data['semester'],
            "year" => $data['year'],
            "start_date" => convertThaiDateToYmd($data['start_date']),
            "end_date" => convertThaiDateToYmd($data['end_date']),
            "random_method" => $data['random_method'],
            "late_time" => $data['late_time'],
        ]);

        $user->courses()->save($course);

        // Return a fresh eloquent instance from the database.
        return $course->fresh();
    }

    public function findAndEnrollStudent(Course $course)
    {
        $students = $this->requestor->searchEnrollment($course);

        $students = $this->userRepo->createUsersFromApiResponse($students);

        $students->each(function ($student) use ($course)  {
            $student->enroll($course);
        });
    }
}
