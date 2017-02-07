<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(AttendCheck\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'password' => bcrypt('secret'),
        'email' => $faker->unique()->safeEmail,
        'title' => $faker->title,
        'name' => $faker->name,
        'lastname' => $faker->lastName,
        'faculty_id' => 1,
        'type_id' => 3,
        'active' => true,
        'pickcount' => random_int(0, 5),
        'remember_token' => str_random(10),
    ];
});

$factory->state(AttendCheck\User::class, 'notActive', function ($faker) {
    return [
        'active' => false,
    ];
});
