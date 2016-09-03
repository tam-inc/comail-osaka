<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    Log::info('info');
    return view('welcome');
});

Route::get('/mail', function () {
    // email sample
    App\Notify::mail('matsuo@tam-tam.co.jp', 'なまえ', 1.5);
});

Route::get('/pickup', 'RiceController@pickup');
Route::get('/pickup_cron', 'RiceController@pickup_cron');
Route::get('/reset', 'RiceController@reset');
