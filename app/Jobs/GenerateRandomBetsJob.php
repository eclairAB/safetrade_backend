<?php

namespace App\Jobs;

use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Asset;
use App\User;
use App\UserBet;

class GenerateRandomBetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;
    protected $user;
    protected $timestamp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Asset $asset,
        User $user,
        CarbonImmutable $timestamp
    ) {
        //
        $this->asset = $asset;
        $this->user = $user;
        $this->timestamp = $timestamp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $choices = [true, false];
        $betAmount = $this->user->betAmount;
        if (!$betAmount) {
            return;
        }
        $count = 1;
        while ($count <= 60) {
            $amount = mt_rand($betAmount->min, $betAmount->max);
            $userBet = UserBet::create([
                'user_id' => $this->user->id,
                'asset_id' => $this->asset->id,
                'timestamp' => $this->timestamp,
                'amount' => $amount,
                'will_go_up' => $choices[array_rand($choices)]
            ]);
            $this->timestamp = $this->timestamp->addSecond();
            $count++;
        }
    }
}
