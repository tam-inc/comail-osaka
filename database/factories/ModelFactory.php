<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Rice::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'date' => $faker->dateTimeThisYear,
        'email' => $faker->safeEmail,
        'ricer' => false,
        'volume' => $faker->randomElement([0.3, 0.5, 0.7, 1.0]),
    ];
});
