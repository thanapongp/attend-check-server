<?php

namespace AttendCheck\Repositories;

use Carbon\Carbon;
use AttendCheck\Course\Course;
use AttendCheck\Course\Period;
use AttendCheck\Course\Schedule;

class ScheduleRepository
{
    /**
     * The day constants.
     */
    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    /**
     * Names of days of the week and their corresponding value.
     *
     * @var array
     */
    private static $days = [
        'อา.'   => self::SUNDAY,
        'จ.'    => self::MONDAY,
        'อ.'    => self::TUESDAY,
        'พ.'    => self::WEDNESDAY,
        'พฤ.'   => self::THURSDAY,
        'ศ.'    => self::FRIDAY,
        'ส.'    => self::SUNDAY,
    ];

    /**
     * Generate course's periods
     * 
     * @param  \AttendCheck\Course\Course $course
     * @param  array  $data
     * @return void 
     */
    public function generatePeriods(Course $course, array $data)
    {
        collect($data['schedules'])->each(function ($item) use ($course) {
            $newPeriodData = [];

            $pieces = explode(' ', str_replace('-', ' ', $item));

            $newPeriodData['day'] = static::$days[$pieces[0]];
            $newPeriodData['start_time'] = $pieces[1];
            $newPeriodData['end_time'] = $pieces[2];
            $newPeriodData['room'] = $pieces[3];

            $course->periods()->save(new Period($newPeriodData));
        }); 
    }

    /**
     * Generate course's schedules.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @return void
     */
    public function generateSchedules(Course $course)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $course->start_date);
        $endDate = Carbon::createFromFormat('Y-m-d', $course->end_date);
        $weekDiff = $startDate->diffInWeeks($endDate) + 1;
        $weekStart = $startDate->startOfWeek();

        $week = 1;

        while ($week <= $weekDiff) {

            $course->periods->each(function ($period) use ($weekStart, $course) {
                $schedule = new Schedule;

                $date = ($period->day == self::MONDAY) ? 
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
}
