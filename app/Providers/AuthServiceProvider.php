<?php

namespace App\Providers;

use App\Policies\AssetBetPolicy;
use App\Policies\UserAssetPolicy;
use App\UserAsset;
use App\UserBet;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//        'App\Model' => 'App\Policies\ModelPolicy',
        UserAsset::class => UserAssetPolicy::class,
        UserBet::class => AssetBetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }
}
