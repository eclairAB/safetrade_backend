<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Asset;
use App\UserBet;
use Faker\Generator as Faker;

$factory->define(UserBet::class, function (Faker $faker) {
    return [
        'asset_id' => function () {
            return factory(Asset::class)->create()->id;
        },
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'timestamp' => $faker->dateTime(),
        'will_go_up' => true,
        'amount' => 0,
    ];
});
