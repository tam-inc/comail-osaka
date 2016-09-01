<?php

return [
    'driver' => 'smtp',
    'host' => 'smtp.sendgrid.net',
    'port' => 587,
    'from' => ['address' => 'tamdevelop@gmail.com', 'name' => '大阪コメール'],
    'encryption' => 'tls',
    'username' => env('SENDGRID_USERNAME'),
    'password' => env('SENDGRID_PASSWORD'),
];
