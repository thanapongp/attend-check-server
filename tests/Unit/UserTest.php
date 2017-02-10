<?php

namespace Tests\Unit;

use AttendCheck\User;
use Tests\BrowserKitTest;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends BrowserKitTest
{
    use DatabaseTransactions;

    /** @test */
    function user_can_be_created()
    {
        $password = bcrypt('secret');

        $user = User::create([
            'username' => 'test',
            'password' => $password,
            'email'    => 'test@test.com',
            'title'    => 'Mr.',
            'name'     => 'Lorem',
            'lastname' => 'Ipsum',
            'faculty_id' => '11',
            'type_id' => '3',
        ]);

        $this->seeInDatabase('users', [
            'username' => 'test',
            'password' => $password,
            'email'    => 'test@test.com',
            'title'    => 'Mr.',
            'name'     => 'Lorem',
            'lastname' => 'Ipsum',
            'faculty_id' => '11',
            'type_id' => '3',
        ]);

        return $user;
    }

    /**
     * @test
     * @depends user_can_be_created
     */
    function user_has_a_type(User $user)
    {
        $this->assertEquals($user->type, 'teacher');
    }

    /**
     * @test
     * @depends user_can_be_created
     */
    function user_has_a_faculty(User $user)
    {
        $this->assertEquals($user->faculty, 'วิทยาศาสตร์');
    }

    /** 
     * @test
     * @depends user_can_be_created
    */
    function user_can_be_approved(User $user)
    {
        $user->approve();
        $this->assertTrue($user->active);
    }
}
