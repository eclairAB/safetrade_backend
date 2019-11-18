<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Asset;
use App\AssetPriceHistory;
use App\User;

class AssetPriceHistoryControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(factory(User::class)->create());
    }

    public function testListPrices()
    {
        $asset = factory(Asset::class)->create();
        $price = factory(AssetPriceHistory::class)->create([
            'asset_id' => $asset->id
        ]);
        $response = $this->get(
            route('assets.price-history.index', ['assetId' => $asset->id])
        );
        $response->assertStatus(Response::HTTP_OK)->assertJsonCount(1);
    }
}
