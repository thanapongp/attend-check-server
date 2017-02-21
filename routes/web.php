<?php

Route::get('/', function () { return redirect('/login'); });
Route::get('/test', 'TestController@create');

Auth::routes();
Route::get('/register-completed', 'Auth\RegisterController@showRegistrationCompletedPage');

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {

    // Dashboard home
    Route::get('/', 'DashboardController@showMainPage');

    // Course
    Route::get('/course/add', 'CourseController@create');
    Route::get('/course/add/search', 'CourseController@showSearchResult');
    Route::post('/course/store', 'CourseController@store');
    
    Route::get('/course/1106209-59', 'CourseController@show');
    Route::get('/course/1106209-59/1', 'CourseController@showSchedule');

    // User
    Route::get('/user/{user}', 'UserController@show');
    Route::post('/user/{user}/approve', 'UserController@approve');

});
