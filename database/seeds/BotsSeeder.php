<?php

use Illuminate\Database\Seeder;

use App\User;
use App\BetAmount;

class BotsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $min = 100.0;
        $max = 500.0;
        foreach (range(1, 5) as $i) {
            $username = "safetrade_bot{$i}";
            $attrs = [
                "username" => $username,
                "name_first" => "Safetrade{$i}",
                "password" => bcrypt("bot_password"),
                "user_level" => "user"
            ];
            $user = User::where([
                "email" => "{$username}@gmail.com"
            ])->first();

            if ($user) {
                $user->fill($attrs);
                $user->save();
            } else {
                $attrs["email"] = "{$username}@gmail.com";
                $user = User::create($attrs);
            }

            $attrs = [
                "min" => $min,
                "max" => $max
            ];
            $betAmount = BetAmount::where([
                "user_id" => $user->id
            ])->first();
            if ($betAmount) {
                $betAmount->fill($attrs);
                $betAmount->save();
            } else {
                $attrs["user_id"] = $user->id;
                BetAmount::create($attrs);
            }
            $min += 200.0;
            $max += 200.0;
        }
    }
}
