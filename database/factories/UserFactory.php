<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => 'password', // password
        'remember_token' => Str::random(10),
        'user_level' => 'test',
        'name_first' => $faker->firstName,
        'name_last' => $faker->lastName,
        'contact_no' => $faker->phoneNumber,
        'birth_date' => $faker->date('Y-m-d', 'now'),
        'zip_code' => $faker->postcode,
        'city' => $faker->city,
        'address' => $faker->address,
        'country' => $faker->countryCode,
        'state' => $faker->state,
    ];
});
