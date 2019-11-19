<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Asset;
use App\AssetPriceHistory;
use App\UserBet;

class ComputeAssetPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $asset;
    protected $timestamp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Asset $asset, $timestamp)
    {
        $this->asset = $asset;
        $this->timestamp = $timestamp;
    }

    private function getBets($fromTimestamp, $toTimestamp, $willGoUp)
    {
        return UserBet::where('will_go_up', $willGoUp)->whereBetween(
            'timestamp',
            [$fromTimestamp, $toTimestamp]
        );
    }

    private function getUpBets($fromTimestamp, $toTimestamp)
    {
        return $this->getBets($fromTimestamp, $toTimestamp, true);
    }

    private function getDownBets($fromTimestamp, $toTimestamp)
    {
        return $this->getBets($fromTimestamp, $toTimestamp, false);
    }

    private function setPrice($lastPrice, $toTimestamp, $upTotal, $downTotal)
    {
        $diff = $upTotal - $downTotal;
        return AssetPriceHistory::create([
            'asset_id' => $this->assetId,
            'timestamp' => $toTimestamp,
            'price' => $lastPrice->price - $diff
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->assetId = $this->asset->id;
        $lastPrice = AssetPriceHistory::where(['asset_id' => $this->assetId])
            ->orderBy('timestamp', 'desc')
            ->get()
            ->first();

        $toTimestamp = $this->timestamp;
        $fromTimestamp = $toTimestamp->subSeconds(8);
        $upBets = $this->getUpBets($fromTimestamp, $toTimestamp);
        $downBets = $this->getDownBets($fromTimestamp, $toTimestamp);

        $upTotal = $upBets->sum('amount');
        $downTotal = $downBets->sum('amount');
        $this->setPrice($lastPrice, $toTimestamp, $upTotal, $downTotal);
    }
}
