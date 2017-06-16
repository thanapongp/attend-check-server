<?php

Route::post('/register', 'Api\DevicesController@register');
Route::post('/changedevice/request', 'Api\DevicesController@requestChangeDeviceToken');
Route::post('/changedevice/change', 'Api\DevicesController@requestDataForNewDevice');

Route::middleware('auth:mobileapp')->group(function () {
    Route::get('/user', 'Api\DevicesController@getUserData');
    Route::get('/user/record', 'Api\DevicesController@getUserAttendanceRecord');
});
