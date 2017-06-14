<?php

Route::get('/', function () { return redirect('/login'); });

Auth::routes();
Route::get('/register-completed', 'Auth\RegisterController@showRegistrationCompletedPage');

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {

    // Dashboard home
    Route::get('/', 'DashboardController@showMainPage');

    // Course
    Route::get('/course/add', 'CourseController@create');
    Route::get('/course/add/search', 'CourseController@showSearchResult');
    Route::post('/course/store', 'CourseController@store');

    Route::get('/course/{course}', 'CourseController@show');
    Route::get('/course/{course}/{schedule}', 'CourseController@showSchedule');
    Route::get('/course/{course}/student/{id}', 'CourseController@showStudentRaw');

    Route::post('/course/{course}/export', 'CourseController@export');
    
    Route::post('/manual-check', 'AttendanceController@attendClass');
    Route::post('/enable-firstcheck', 'ScheduleController@generateFirstCheckCode');

    Route::get('/random-student', 'ScheduleController@getRandomStudent');
    Route::post('/add-point-student', 'ScheduleController@addPointToCandidate');

    // User
    Route::get('/user/all', 'UserController@index');
    Route::get('/user/{user}', 'UserController@show');
    Route::get('/student/{id}', 'UserController@showStudent');
    Route::post('/user/{user}/approve', 'UserController@approve');
    Route::post('/user/{user}/delete', 'UserController@destroy');

});
