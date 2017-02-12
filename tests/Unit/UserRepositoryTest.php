<?php

namespace Tests\Unit;

use Tests\BrowserKitTest;
use AttendCheck\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRepositoryTest extends BrowserKitTest
{
    use DatabaseTransactions;

    /** @test */
    function it_can_create_multiple_users_from_an_array_of_stdObject()
    {
        $users = [
            (object) [
            "STUDENTCODE" => "5511404150",
            "PREFIXNAME" => "นางสาว",
            "STUDENTNAME" => "วรรณวิสา",
            "STUDENTSURNAME" => "มณีพูล",
            "PREFIXNAMEENG" => "Miss",
            "STUDENTNAMEENG" => "Wanvisa",
            "STUDENTSURNAMEENG" => "Maneepool",
            "FACULTYID" => "11",
            "FACULTYNAME" => "คณะวิทยาศาสตร์",
            ],

            (object) [
            "STUDENTCODE" => "5611401260",
            "PREFIXNAME" => "นางสาว",
            "STUDENTNAME" => "อภิญญา",
            "STUDENTSURNAME" => "ทองจรูญ",
            "PREFIXNAMEENG" => "Miss",
            "STUDENTNAMEENG" => "Apinya",
            "STUDENTSURNAMEENG" => "Thongjaroon",
            "FACULTYID" => "11",
            "FACULTYNAME" => "คณะวิทยาศาสตร์",
            ],
        ];

        $repository = new UserRepository;

        $repository->createUsersFromApiResponse($users);

        $this->seeInDatabase('users', ['username' => '5511404150', 'password' => null]);
        $this->seeInDatabase('users', ['username' => '5611401260', 'password' => null]);
    }
}
