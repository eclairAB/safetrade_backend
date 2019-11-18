<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

use App\Asset;
use App\User;
use App\UserBet;

class GenerateRandomBets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:random-bets {asset_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates random bets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Update to support multiple bots
        $user = User::where('username', 'safetrade_bot')->first();
        $asset = Asset::get()
            ->where('name', $this->argument('asset_name'))
            ->first();

        if ($asset && $user) {
            $now = Carbon::now();
            $max = CarbonImmutable::now()->add(60, 'second');
            $lastBet = UserBet::where([
                ['user_id', $user->id],
                ['asset_id', $asset->id]
            ])
                ->orderBy('timestamp', 'desc')
                ->get()
                ->first();

            $lastTimestamp = $lastBet ? $lastBet->timestamp : $now;
            $choices = [true, false];
            while ($lastTimestamp <= $max) {
                UserBet::create([
                    'user_id' => $user->id,
                    'asset_id' => $asset->id,
                    'timestamp' => $lastTimestamp,
                    'amount' => 5,
                    'will_go_up' => $choices[array_rand($choices)]
                ]);
                $lastTimestamp->add(1, 'second');
            }
        }
    }
}
