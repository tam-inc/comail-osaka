<?php

return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.sendgrid.net'),
    'port' => env('MAIL_PORT', 587),
    'from' => ['address' => 'tam-ml@tam-tam.co.jp', 'name' => 'コメール'],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('SENDGRID_USERNAME', null),
    'password' => env('SENDGRID_PASSWORD', null),
    'sendmail' => '/usr/sbin/sendmail -bs',
];
