<?php

namespace App\Jobs;

use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

use App\Asset;
use App\User;

class GenerateRandomBetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Asset $asset, User $user)
    {
        //
        $this->asset = $asset;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assetId = $this->asset->id;
        $userId = $this->user->id;
        $betAmount = $this->user->betAmount;
        if (!$betAmount) {
            return;
        }
        $minBet = $betAmount->min;
        $maxBet = $betAmount->max;
        DB::select("
            INSERT INTO user_bets (user_id, asset_id, timestamp, amount, will_go_up)
            SELECT
                $userId, $assetId, timestamp, random_between($minBet::int, $maxBet::int),
                random_between(0, 1)::boolean
            FROM
                generate_series(
                    date_trunc('second', CURRENT_TIMESTAMP),
                    date_trunc('second', CURRENT_TIMESTAMP + interval '1' day),
                    '1sec'
                ) AS timestamp
            ON CONFLICT DO NOTHING
        ");
    }
}
