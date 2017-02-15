<?php

namespace Tests\Unit;

use AttendCheck\User;
use Tests\BrowserKitTest;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseRepositoryTest extends BrowserKitTest
{
    use DatabaseTransactions;
    
    /**
     * Instance of teacher user.
     * 
     * @var \AttendCheck\User
     */
    public $user;
    
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);

        $this->repository = resolve('\AttendCheck\Repositories\CourseRepository');
    }

    /** @test */
    function it_can_create_a_new_course()
    {
        $course = $this->createNewCourse();

        $this->seeInDatabase('courses', [
            'code' => '1106204',
            'name' => 'ระบบฐานข้อมูล',
            'section' => 1,
            'semester' => 'ภาคปลาย',
            'year' => 2559,
            'teacher_id' => $this->user->id,
            'start_date' => '2017-02-14',
            'end_date' => '2017-03-31',
        ]);

        $this->seeInDatabase('periods', [
            "course_id" => $course->id,
            "day" => 2,
            "start_time" => "13:00",
            "end_time" => "14:50",
            "room" => "SC412",
        ]);

        $this->seeInDatabase('periods', [
            "course_id" => $course->id,
            "day" => 5,
            "start_time" => "13:00",
            "end_time" => "15:50",
            "room" => "SC443",
        ]);
    }

    /**
     * @test
     */
    function it_can_find_and_enroll_students_into_course()
    {
        $course = $this->createNewCourse();

        $this->repository->findAndEnrollStudent($course);

        $this->seeInDatabase('users', ['username' => '5511404150', 'password' => null]);
        $this->seeInDatabase('users', ['username' => '5611401260', 'password' => null]);

        $this->seeInDatabase('enrollments', ['course_id' => $course->id]);
    }

    public function createNewCourse()
    {
        $data = [
            'code' => '1106204',
            'name' => 'ระบบฐานข้อมูล',
            'section' => 1,
            'semester' => 'ภาคปลาย',
            'year' => 2559,
            'start_date' => '7 ก.พ. 2017',
            'end_date' => '31 มี.ค. 2017',
            "random_method" => 1,
            "late_time" => 15,
            "schedules" => [
                "อ. 13:00-14:50 SC412",
                "ศ. 13:00-15:50 SC443",
            ],
            "start_date" => "14 ก.พ. 2017",
            "end_date" => "31 มี.ค. 2017",
        ];

        return $this->repository->create($data);
    }
}
