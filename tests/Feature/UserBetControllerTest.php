<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;

use Tests\TestCase;
use App\Asset;
use App\User;
use App\UserBet;

class UserBetControllerTest extends TestCase
{
    use DatabaseTransactions;

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
    public function testListUserBets()
    {
        $asset = factory(Asset::class)->create();
        factory(UserBet::class)->create([
            'asset_id' => $asset->id,
            'user_id' => $this->user->id,
        ]);

        // Create bet from other user
        factory(UserBet::class)->create([
            'asset_id' => $asset->id,
            'user_id' => factory(User::class)->create()->id,
        ]);

        $response = $this->get(route('bets.index'));
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.user_id', $this->user->id);
    }
}
