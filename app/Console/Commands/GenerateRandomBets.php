<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

use App\Jobs\GenerateRandomBets as GenerateRandomBetsJob;
use App\Asset;
use App\User;

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
        $this->info('Start: GenerateRandomBets');
        $timestamp = CarbonImmutable::now()->startOfSecond();
        // Update to support multiple bots
        $botUsers = User::where('username', 'LIKE', 'safetrade_bot%')->get();
        $asset = Asset::get()
            ->where('name', $this->argument('asset_name'))
            ->first();
        if (!$asset) {
            $this->info('No asset found: ' . $asset->name);
            return;
        }

        foreach ($botUsers as $user) {
            GenerateRandomBetsJob::dispatch($asset, $user, $timestamp);
            $this->info('Generating bet for user: ' . $user->username);
        }
        $this->info('End: GenerateRandomBets');
    }
}
