<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Records\FoodMenuItemRecord;
use Tests\Feature\Api\Version1\Records\FoodMenuRecord;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;

class FoodMenuControllerShowTest extends ApiControllerTestCase {

    use FoodMenuAction;

    public function test_show_failed_when_id_missing() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/menu/show');

        $response
            ->assertStatus(200)
            ->assertSee("");
    }

    public function test_show_failed_when_id_not_exists() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/menu/show/9999');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'errors' => [
                    'id'
                ],
            ]);
    }

    public function test_show_ok_when_id_exists() {
        $foodMenu = new FoodMenuRecord(
            start_at: "2021-12-08 19:24:00",
            foods: [
                new FoodMenuItemRecord(name: "apple", unit: "per", quantity: 1),
                new FoodMenuItemRecord(name: "orange", unit: "per", quantity: 2),
            ],
            tags: ["dinner", "testing", "nice"],
            remark: "",
        );

        $this->createFoodMenu($foodMenu);

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/menu/show/1');

        $response
            ->assertStatus(200)
            ->assertJson([
                'ok'   => true,
                'data' => $foodMenu->toArray()
            ]);
    }

    public function test_show_ok_when_id_exists_and_it_is_second_menu() {
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
            ->get('/api/v1/food/menu/show/2');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data',
            ])
            ->assertJsonPath("data.id", 2)
            ->assertJsonCount(3, "data.foods")
            ->assertJsonFragment([
                "tags" => ["breakfast", "testing", "good"]
            ]);
    }

}
