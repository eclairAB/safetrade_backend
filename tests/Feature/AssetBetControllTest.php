<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Asset;
use App\User;

class AssetBetControllTest extends TestCase
{
    use DatabaseTransactions;
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Passport::actingAs($this->user);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        $asset = factory(Asset::class)->create();

        $response = $this->post(
            route('assets.bets.store', ['asset' => $asset->id]),
            [
                'amount' => 100,
                'will_go_up' => true,
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED)->assertJson([
            'asset_id' => $asset->id,
            'user_id' => $this->user->id,
            'amount' => 100,
            'will_go_up' => true,
        ]);
        $this->assertArrayHasKey('timestamp', $response->json());
    }
}
