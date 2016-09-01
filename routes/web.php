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
    $body = "本文\nテキストテキスト";
    Mail::raw($body, function($message)
    {
        $message->to('matsuo@tam-tam.co.jp')->subject('コメール');
    });

    Log::info('mail sent');
});

Route::get('/pickup', 'RiceController@pickup');
Route::get('/pickup_cron', 'RiceController@pickup_cron');
Route::get('/reset', 'RiceController@reset');
