<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

use App\Events\AssetPriceUpdated;
use App\Asset;
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

    private function getInterval()
    {
        $key = 'price-computation-interval';
        return Cache::get($key, function () {
            return config('assets.price_computation_interval');
        });
    }

    private function getAssetId($name)
    {
        $key = 'asset-' . $name;
        return Cache::get($key, function () {
            $asset = Asset::get()
                ->where('name', $this->argument('asset_name'))
                ->first();
            if (!$asset) {
                return null;
            }
            return $asset->id;
        });
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timestamp = CarbonImmutable::now()->startOfSecond();
        $assetName = $this->argument('asset_name');
        $this->info('Start: ComputeAssetPrice job for asset: ' . $assetName);

        $assetId = $this->getAssetId($assetName);
        $interval = $this->getInterval();

        $count = 1;
        $betCounts = random_int(10, 20);

        $bets = [];

        foreach (range(1, $betCounts) as $count) {
            array_push($bets, random_int(1, 60));
        }

        while ($count <= 60) {
            $newPrice = \DB::select("
                INSERT INTO asset_price_histories(asset_id, timestamp, price)
                SELECT $assetId, '$timestamp'::timestamptz, (price_histories.price - (up_total - down_total)) FROM (
                    SELECT price FROM asset_price_histories ORDER BY timestamp DESC LIMIT 1) AS price_histories,
                    (SELECT
                        SUM(CASE WHEN will_go_up = true THEN amount ELSE 0 END)
                            AS up_total,
                        SUM(CASE WHEN will_go_up = false THEN amount ELSE 0 END)
                            AS down_total
                    FROM
                        user_bets
                    WHERE
                        asset_id = 1
                    AND timestamp BETWEEN '$timestamp'::timestamptz - INTERVAL '$interval' AND '$timestamp'::timestamptz) AS bets
                RETURNING price;
            ")[0];

            $userBet = null;
            if (in_array($count, $bets)) {
                $userBet = UserBet::whereBetween('timestamp', [
                    $timestamp,
                    $timestamp->endOfSecond(),
                ])->first();
            }

            while ($timestamp == CarbonImmutable::now()->startOfSecond()) {
                // Wait until it's time
            }
            event(
                new AssetPriceUpdated(
                    $assetId,
                    $timestamp->timestamp,
                    $newPrice->price,
                    $userBet
                )
            );
            $count++;
            $timestamp = $timestamp->addSecond();
        }
        $this->info('End: ComputeAssetPrice job for asset: ' . $assetName);
    }
}
