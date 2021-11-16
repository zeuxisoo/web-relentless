<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodUnit;

use App\Models\FoodUnit;
use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodUnitAction;

class FoodUnitControllerListTest extends ApiControllerTestCase {

    use FoodUnitAction;

    public function test_list_ok_when_query_correct() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/list');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data",
                "meta"
            ])
            ->assertJsonCount(0, "data");
    }

    public function test_list_ok_when_response_data_correct() {
        $this->createFoodUnit('cup');
        $this->createFoodUnit('glass');

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/list');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data",
                "meta"
            ])
            ->assertJsonCount(2, "data");
    }

    public function test_list_ok_when_paginate_correct() {
        // Setup auth user (e.g. make `$item->user_id = Auth::id()` not null) before create item
        $this->actingAs($this->currentUser);

        FoodUnit::factory()->count(10)->create([
            "name" => time(),
        ]);

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/list');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data",
                "meta"
            ])
            ->assertJsonCount(8, "data");
    }

}
