<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

use App\Jobs\ComputeAssetPrice as ComputeAssetPriceJob;
use App\Asset;

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
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $timestamp = CarbonImmutable::now();
        $asset = Asset::get()
            ->where('name', $this->argument('asset_name'))
            ->first();
        if (!$asset) {
            return;
        }
        $this->info('Start: ComputeAssetPrice job for asset: ' . $asset->name);
        $count = 1;
        while ($count <= 60) {
            ComputeAssetPriceJob::dispatch($asset, $timestamp);
            $count++;
            $timestamp = $timestamp->addSecond();
            sleep(1);
        }
        $this->info('End: ComputeAssetPrice job for asset: ' . $asset->name);
    }
}
