<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use App\Models\FoodMenu;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Records\FoodMenuItemRecord;
use Tests\Feature\Api\Version1\Records\FoodMenuRecord;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;

class FoodMenuControllerListTest extends ApiControllerTestCase {

    use FoodMenuAction;

    public function test_list_ok_when_query_correct() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/menu/list');

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
        $foodMenus = [
            1 => new FoodMenuRecord(
                start_at: "2021-12-08 19:24:00",
                foods: [
                    new FoodMenuItemRecord(name: "apple", unit: "per", quantity: 1),
                    new FoodMenuItemRecord(name: "orange", unit: "per", quantity: 2),
                ],
                tags: ["dinner", "testing", "nice"],
                remark: "",
            ),
            2 => new FoodMenuRecord(
                start_at: "2021-12-08 19:48:00",
                foods: [
                    new FoodMenuItemRecord(name: "banana", unit: "per", quantity: 3),
                    new FoodMenuItemRecord(name: "kiwi", unit: "per", quantity: 4),
                    new FoodMenuItemRecord(name: "pitaya", unit: "per", quantity: 5),
                ],
                tags: ["breakfast", "testing", "good"],
                remark: "",
            )
        ];

        foreach($foodMenus as $foodMenu) {
            $this->createFoodMenu($foodMenu);
        }

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/menu/list');

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
        // Setup auth user (e.g. make `$item->` not null) before create item
        $this->actingAs($this->currentUser);

        FoodMenu::factory()->count(10)->create();

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/menu/list');

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
