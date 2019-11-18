<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Asset;
use App\AssetPriceHistory;
use Faker\Generator as Faker;

$factory->define(AssetPriceHistory::class, function (Faker $faker) {
    return [
        'asset_id' => function () {
            return factory(Asset::class)->create()->id;
        },
        'timestamp' => $faker->dateTime(),
        'price' => $faker->randomFloat(3, 100, 1000)
    ];
});
