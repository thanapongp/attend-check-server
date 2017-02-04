<?php

namespace Tests\Feature;

use Tests\BrowserKitTest;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends BrowserKitTest
{
    use DatabaseTransactions;

    /** @test */
    function user_can_visit_register_page()
    {
        $this->visit('/register')
             ->see('ลงทะเบียน');
    }

    /** @test */
    function user_can_register_to_the_site()
    {
        $this->visit('/register')
             ->type('นาย', 'title')
             ->type('ทดสอบ', 'name')
             ->type('ทดสอบ', 'lastname')
             ->select('3', 'type')
             ->type('test@test.com', 'email')
             ->type('test1234', 'username')
             ->type('secret1234', 'password')
             ->type('secret1234', 'password_confirmation')
             ->press('submit');

        $this->seePageIs('/register-completed');

        $this->seeInDatabase('users', [
            'title' => 'นาย',
            'name'  => 'ทดสอบ',
            'lastname' => 'ทดสอบ',
            'type_id' => '3',
            'faculty_id' => '1',
            'email' => 'test@test.com',
            'username' => 'test1234',
            'active' => '0'
        ]);
    }
}
