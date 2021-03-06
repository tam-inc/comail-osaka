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
    // basic auth
    if (env('APP_ENV') == 'production') {
        switch (true) {
            case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
            case $_SERVER['PHP_AUTH_USER'] !== env('AUTH_USER'):
            case $_SERVER['PHP_AUTH_PW']   !== env('AUTH_PW'):
                header('WWW-Authenticate: Basic realm="Enter username and password."');
                header('Content-Type: text/plain; charset=utf-8');
                die('401 Unauthorized.');
        }
    }

    $rice = new App\Rice();
    return view('top', [
        'ricers' => $rice->getResult(),
    ]);
});


Route::get('/mail_test', function () {
    // email test
    App\Notify::mail('matsuo@tam-tam.co.jp', 'なまえ', 1.5);
});


Route::get('/slack_test', function () {
    // slack test
    Slack::send('テストです');
});


Route::get('/pickup_cron', 'RiceController@pickup_cron');
Route::get('/reset', 'RiceController@reset');
Route::get('/cleanup_cron', 'RiceController@cleanup_cron');
