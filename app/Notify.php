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
            function($message) {
                $message
                    ->to('matsuo@tam-tam.co.jp')
                    ->subject('【コメール】本日の米炊き当番に決定！！');

                Log::info('mail sent');
            }
        );
    }
}
