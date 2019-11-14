<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Asset;
use Faker\Generator as Faker;

$factory->define(Asset::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->currencyCode
    ];
});
