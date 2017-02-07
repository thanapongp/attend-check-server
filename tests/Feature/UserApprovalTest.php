<?php

namespace Tests\Feature;

use AttendCheck\User;
use Tests\BrowserKitTest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserApprovalTest extends BrowserKitTest
{
    use DatabaseTransactions;

    public $normalUser;

    public $staffUser;

    public function setUp()
    {
        parent::setUp();

        $this->normalUser = factory(User::class)->states('notActive')->create();
        $this->staffUser  = factory(User::class)->create();
    }

    /** @test */
    function user_cant_login_if_not_actived()
    {
        $this->visit('/login')
             ->type($this->normalUser->username, 'username')
             ->type('secret', 'password')
             ->press('submit');

        $this->assertEquals(Auth::user(), null);
        $this->seePageIs('/login');
        $this->see('User นี้ยังไมได้รับการยืนยันข้อมูล');
    }

    /** @test */
    function user_can_login_if_activated()
    {
        $this->visit('/login')
             ->type($this->staffUser->username, 'username')
             ->type('secret', 'password')
             ->press('submit');

        $this->assertEquals(Auth::user()->username, $this->staffUser->username);
        $this->seePageIs('/dashboard');
    }
}
