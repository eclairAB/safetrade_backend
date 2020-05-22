<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Asset;
use App\User;

class AssetControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(factory(User::class)->create());
    }

    public function testListAssets()
    {
        factory(Asset::class)->create();
        $response = $this->get(route('assets.index'));
        $response->assertStatus(Response::HTTP_OK)->assertJsonCount(1);
    }

    public function testShowAsset()
    {
        $asset = factory(Asset::class)->create();
        fwrite(STDERR, print_r($asset->id, true));
        $response = $this->get(route('assets.show', ['asset' => $asset->id]));
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'id' => $asset->id,
        ]);
    }

    public function testCreateAsset()
    {
        $response = $this->json('POST', route('assets.index'), [
            'name' => 'test',
            'description' => 'description',
        ]);
        $response->assertStatus(Response::HTTP_CREATED)->assertJson([
            'name' => 'test',
            'description' => 'description',
        ]);
    }

    public function testUpdateAsset()
    {
        $asset = factory(Asset::class)->create();
        $response = $this->json(
            'PATCH',
            route('assets.update', ['asset' => $asset->id]),
            [
                'name' => 'new name',
                'description' => 'new description',
            ]
        );
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'name' => 'new name',
            'description' => 'new description',
        ]);
    }

    public function testDeleteAsset()
    {
        $asset = factory(Asset::class)->create();
        $response = $this->delete(
            route('assets.destroy', ['asset' => $asset->id])
        );
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
