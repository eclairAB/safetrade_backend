<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

use App\User;
use App\BetAmount;
use App\Asset;
use App\AssetPriceHistory;

class BotsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $asset = Asset::firstOrCreate([
            'name' => 'cash',
        ]);
        $price = AssetPriceHistory::where([
            'asset_id' => $asset->id,
        ])->first();

        if (!$price) {
            AssetPriceHistory::create([
                'asset_id' => $asset->id,
                'price' => 50000000.0,
                'timestamp' => Carbon::now(),
            ]);
        }
        $min = 100.0;
        $max = 500.0;
        foreach (range(1, 5) as $i) {
            $username = "safetrade_bot{$i}";
            $user = User::updateOrCreate(
                [
                    "email" => "{$username}@gmail.com",
                ],
                [
                    "username" => $username,
                    "name_first" => "Safetrade{$i}",
                    "password" => bcrypt("bot_password"),
                    "user_level" => "user",
                ]
            );

            BetAmount::updateOrCreate(
                [
                    "user_id" => $user->id,
                ],
                [
                    "min" => $min,
                    "max" => $max,
                ]
            );
            $min += 200.0;
            $max += 200.0;
        }
    }
}
