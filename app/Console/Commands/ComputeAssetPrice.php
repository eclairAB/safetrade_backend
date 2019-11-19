<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

use App\Asset;
use App\AssetPriceHistory;
use App\UserBet;

class ComputeAssetPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:compute-price {asset_name}';
    protected $assetId;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Computes asset price based on bets.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = CarbonImmutable::now();
        $asset = Asset::get()
            ->where('name', $this->argument('asset_name'))
            ->first();
        if (!$asset) {
            return;
        }
        $this->assetId = $asset->id;
        $lastPrice = AssetPriceHistory::where(['asset_id' => $this->assetId])
            ->orderBy('timestamp', 'desc')
            ->get()
            ->first();

        $toTimestamp = $lastPrice
            ? CarbonImmutable::parse($lastPrice->timestamp)->addSecond()
            : $now;
        while ($toTimestamp <= $now) {
            $fromTimestamp = $toTimestamp->subSeconds(8);
            $upBets = $this->getUpBets($fromTimestamp, $toTimestamp);
            $downBets = $this->getDownBets($fromTimestamp, $toTimestamp);

            $upTotal = $upBets->sum('amount');
            $downTotal = $downBets->sum('amount');

            $lastPrice = $this->setPrice(
                $lastPrice,
                $toTimestamp,
                $upTotal,
                $downTotal
            );
            $toTimestamp = $toTimestamp->add(1, 'second');
        }
    }
}
