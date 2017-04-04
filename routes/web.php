<?php

Route::get('/', function () { return redirect('/login'); });
Route::get('/test', function () {
    return \AttendCheck\User::find(140)->enrollments()->toSql();
});

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
    
    Route::post('/manual-check', 'AttendanceController@attendClass');
    Route::post('/enable-firstcheck', 'ScheduleController@generateFirstCheckCode');

    // User
    Route::get('/user/{user}', 'UserController@show');
    Route::post('/user/{user}/approve', 'UserController@approve');

});
