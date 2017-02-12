<?php

namespace Tests\Feature;

use AttendCheck\User;
use Tests\BrowserKitTest;
use AttendCheck\Course\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseCRUDTest extends BrowserKitTest
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
    }

    /** @test */
    function course_can_be_searched_by_teacher()
    {
        $this->visit('/dashboard/course/add')
             ->type('1106204', 'course')
             ->type('2', 'section')
             ->select('2', 'semester')
             ->select('2559', 'year')
             ->press('ค้นหารายวิชา');

        $this->seePageIs(
            '/dashboard/course/add/search?course=1106204&section=2&semester=2&year=2559'
        );

        $this->see('ขั้นที่ 2')
             ->see('1106204');
    }

    /** @test */
    function course_can_be_created_and_automatically_import_enrolled_students()
    {
        // Skip the form typing stuff.
        $this->visit(
            '/dashboard/course/add/search?course=1106204&section=1&semester=2&year=2559'
        );

        $this->see('ขั้นที่ 2')
             ->see('1106204');

        $this->type('7 ก.พ. 2017', 'start_date')
             ->type('31 มี.ค. 2017', 'end_date')
             ->press('เพิ่มรายวิชา');

        $this->seeInDatabase('courses', [
            'code' => '1106204',
            'name' => 'ระบบฐานข้อมูล',
            'section' => 1,
            'semester' => 'ภาคปลาย',
            'year' => 2559,
            'teacher_id' => $this->user->id,
            'start_date' => '2017-02-07',
            'end_date' => '2017-03-31',
        ]);

        $courseID = Course::latest()->first()->id;

        $this->seeInDatabase('users', ['username' => '5511404150', 'password' => null]);
        $this->seeInDatabase('users', ['username' => '5611401260', 'password' => null]);

        $this->seeInDatabase('enrollments', ['course_id' => $courseID]);
    }
}
