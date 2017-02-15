<?php

namespace AttendCheck\Repositories;

use Carbon\Carbon;
use Illuminate\Http\Request;
use AttendCheck\Api\Requestor;
use AttendCheck\Course\Course;
use AttendCheck\Course\Period;
use AttendCheck\Course\Schedule;
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
            "late_time" => $data['late_time'],
        ]);

        $user->courses()->save($course);

        $this->generatePeriods($course->fresh(), $data);

        $this->generateSchedules($course->fresh());

        return $course->fresh();
    }

    private function generatePeriods(Course $course, array $data)
    {
        $periods = collect($data['schedules'])->map(function ($item) {
            $newItem = [];

            $pieces = explode(' ', str_replace('-', ' ', $item));

            $newItem['day'] = $this->getDay($pieces[0]);
            $newItem['start_time'] = $pieces[1];
            $newItem['end_time'] = $pieces[2];
            $newItem['room'] = $pieces[3];

            return $newItem;
        });

        $periods->each(function ($period) use ($course) {
            $newPeriod = new Period;
            $newPeriod->day = $period['day'];
            $newPeriod->start_time = $period['start_time'];
            $newPeriod->end_time = $period['end_time'];
            $newPeriod->room = $period['room'];

            $course->periods()->save($newPeriod);
        });
    }

    private function generateSchedules(Course $course)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $course->start_date);
        $endDate = Carbon::createFromFormat('Y-m-d', $course->end_date);
        $weekDiff = $startDate->diffInWeeks($endDate) + 1;
        $weekStart = $startDate->startOfWeek();

        $week = 1;

        while ($week <= $weekDiff) {

            $course->periods->each(function ($period) use ($weekStart, $course) {
                $schedule = new Schedule;

                $date = ($period->day == 1) ? 
                $weekStart->toDateString() : $weekStart->next($period->day);

                $schedule->start_date = Carbon::parse(
                    $date->toDateString() . ' ' . $period->start_time
                );

                $schedule->end_date = Carbon::parse(
                    $date->toDateString() . ' ' . $period->end_time
                );

                $schedule->room = $period->room;

                $course->schedules()->save($schedule);
            });

            $week++;

            $weekStart = $weekStart->next()->startOfWeek();
        }
    }

    public function findAndEnrollStudent(Course $course)
    {
        $students = $this->requestor->searchEnrollment($course);

        $students = $this->userRepo->createUsersFromApiResponse($students);

        $students->each(function ($student) use ($course)  {
            $student->enroll($course);
        });
    }

    public function getDay($day)
    {
        switch ($day) {
            case 'จ.':
                return 1;
            case 'อ.':
                return 2;
            case 'พ.':
                return 3;
            case 'พฤ.':
                return 4;
            case 'ศ.':
                return 5;
            case 'ส.':
                return 6;
            case 'อา.':
                return 0;
        }
    }
}
