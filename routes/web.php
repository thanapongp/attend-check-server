<?php

Route::get('/', function () { return redirect('/login'); });

Auth::routes();
Route::get('/register-completed', 'Auth\RegisterController@showRegistrationCompletedPage');

Route::get('/dashboard', 'DashboardController@showMainPage');
Route::get('/dashboard/course/add', 'CourseController@create');
Route::get('/dashboard/course/1106209-59', 'CourseController@show');
Route::get('/dashboard/course/1106209-59/1', 'CourseController@showSchedule');