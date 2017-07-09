<?php

namespace AttendCheck\Repositories;

use Illuminate\Http\Request;
use AttendCheck\Api\Requestor;
use AttendCheck\Course\Course;
use Illuminate\Support\Facades\Auth;
use AttendCheck\Repositories\UserRepository;
use AttendCheck\Repositories\ScheduleRepository;

class CourseRepository
{
    /**
     * Instance of API requestor
     * 
     * @var \AttendCheck\Api\Requestor
     */
    protected $requestor;

    /**
     * Instance of user repository
     * 
     * @var \AttendCheck\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * Instance of schedule repository
     * 
     * @var \AttendCheck\Repositories\ScheduleRepository
     */
    protected $scheduleRepo;

    /**
     * Create new instance of class.
     * 
     * @param \AttendCheck\Api\Requestor                    $requestor 
     * @param \AttendCheck\Repositories\UserRepository      $userRepo
     * @param \AttendCheck\Repositories\ScheduleRepository  $scheduleRepo
     */
    public function __construct(
        Requestor $requestor, 
        UserRepository $userRepo,
        ScheduleRepository $scheduleRepo
        )
    {
        $this->requestor = $requestor;
        $this->userRepo = $userRepo;
        $this->scheduleRepo = $scheduleRepo;
    }

    /**
     * Query a course from API and save it to database.
     * 
     * @param  array $data
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
            "late_time" => $data['late_time'],
        ]);

        $user->courses()->save($course);

        $this->scheduleRepo->generatePeriods($course->fresh(), $data);

        $this->scheduleRepo->generateSchedules($course->fresh());

        return $course->fresh();
    }

    public function courseExisted($response)
    {
        return Course::where([
            ['code', '=', $response->COURSECODE],
            ['semester', '=', getSemester($response->SEMESTER)],
            ['year', '=', $response->ACADYEAR],
            ['section', '=', $response->SUBDETAIL[0]->SECTION]
        ])->exists();
    }

    public function findFromURL($url)
    {
        $url = explode('-', $url);

        return Course::where([
            ['code', $url[0]],
            ['semester', semesterValue($url[1])],
            ['year', '25' . $url[2]],
            ['section', $url[3]],
        ])->firstOrFail();
    }

    /**
     * Fetch enrolled students from TQF and save it to our database.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @return void
     */
    public function findAndEnrollStudent(Course $course)
    {
        $students = $this->userRepo->createUsersFromApiResponse(
            $this->requestor->searchEnrollment($course)
        );

        $students->each->enroll($course);
    }
}
