<?php

namespace App;

use Mail;
use Log;

/**
 * Class Notify
 * @package App
 */
class Notify
{
    /**
     * send notify email
     * @param $to
     * @param $name
     * @param $volume
     */
    static function mail($to, $name, $volume)
    {
        Mail::send(
            ['text' => 'ricer-email'],
            ['name' => $name, 'volume' => $volume],
            function($message) use ($to) {
                $message
                    ->to($to)
                    ->subject('【コメール】本日の米炊き当番に決定！！');

                Log::info('mail sent');
            }
        );
    }

    static function cleanupMail($to, $name)
    {
        Mail::send(
            ['text' => 'ricerCleanUp-email'],
            ['name' => $name],
            function ($message) use($to) {
                $message
                    ->to($to)
                    ->subject('【コメール】ごちそうさまでした、お片付けありがとうございます！！');

                Log::info('mail sent');
            }
        );
    }
}
