<?php

namespace Tests\Feature;

use AttendCheck\User;
use Tests\BrowserKitTest;
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

        $this->seeJson([
            'COURSECODE' => '1106204',
            'SEMESTER' => '2',
            'ACADYEAR' => '2559'
        ]);
    }
}
