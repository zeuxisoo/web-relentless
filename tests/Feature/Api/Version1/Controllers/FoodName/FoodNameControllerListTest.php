<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodName;

use App\Models\FoodName;
use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodNameAction;

class FoodNameControllerListTest extends ApiControllerTestCase {

    use FoodNameAction;

    public function test_list_ok_when_query_correct() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/list');

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
        $this->createFoodName('apple');
        $this->createFoodName('orange');

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/list');

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

        FoodName::factory()->count(10)->create([
            "name" => time(),
        ]);

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/list');

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
