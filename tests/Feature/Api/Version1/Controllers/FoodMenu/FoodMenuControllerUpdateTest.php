<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use App\Models\FoodMenuItem;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Records\FoodMenuItemRecord;
use Tests\Feature\Api\Version1\Records\FoodMenuRecord;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;

class FoodMenuControllerUpdateTest extends ApiControllerTestCase {

    use FoodMenuAction;

    public function test_update_failed_when_id_empty() {
        $response = $this->updateFoodMenu('');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "id"
                ]
            ]);
    }

    public function test_update_failed_when_id_not_exists() {
        $this->createDefaultFoodMenu(); // id: 1

        $response = $this->updateFoodMenu(2, new FoodMenuRecord(
            start_at: '2021-12-22 20:04:00',
            foods: [
                new FoodMenuItemRecord(id: '1', name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(id: '2', name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(id: '3', name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "id"
                ]
            ]);
    }

    public function test_update_failed_when_start_at_is_empty() {
        $response   = $this->createDefaultFoodMenu();
        $foodMenuId = $response->json('data.id');

        $response = $this->updateFoodMenu($foodMenuId, new FoodMenuRecord(
            start_at: '',
            foods: [
                new FoodMenuItemRecord(id: '1', name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(id: '2', name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(id: '3', name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "start_at"
                ]
            ]);
    }

    public function test_update_failed_when_start_at_format_invalid() {
        $response   = $this->createDefaultFoodMenu();
        $foodMenuId = $response->json('data.id');

        $response = $this->updateFoodMenu($foodMenuId, new FoodMenuRecord(
            start_at: '2021-12-22 20:04:aa',
            foods: [
                new FoodMenuItemRecord(id: '1', name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(id: '2', name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(id: '3', name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "start_at"
                ]
            ]);
    }

    public function test_update_failed_when_foods_is_empty() {
        $response   = $this->createDefaultFoodMenu();
        $foodMenuId = $response->json('data.id');

        $response = $this->updateFoodMenu($foodMenuId, new FoodMenuRecord(
            start_at: '2021-12-22 20:04:00',
            foods: [],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "foods"
                ]
            ]);
    }

    public function test_update_failed_when_foods_is_not_array() {
        $response   = $this->createDefaultFoodMenu();
        $foodMenuId = $response->json('data.id');

        $response = $this->updateFoodMenu($foodMenuId, new FoodMenuRecord(
            start_at: '2021-12-22 20:04:00',
            foods: 'foods',
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "foods"
                ]
            ]);
    }

    public function test_update_failed_when_tags_is_empty() {
        $response   = $this->createDefaultFoodMenu();
        $foodMenuId = $response->json('data.id');

        $response = $this->updateFoodMenu($foodMenuId, new FoodMenuRecord(
            start_at: '2021-12-22 20:04:00',
            foods: [
                new FoodMenuItemRecord(id: '1', name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(id: '2', name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(id: '3', name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: [],
            remark: '',
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "tags"
                ]
            ]);
    }

    public function test_update_failed_when_tags_is_not_array() {
        $response   = $this->createDefaultFoodMenu();
        $foodMenuId = $response->json('data.id');

        $response = $this->updateFoodMenu($foodMenuId, new FoodMenuRecord(
            start_at: '2021-12-22 20:04:00',
            foods: [
                new FoodMenuItemRecord(id: '1', name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(id: '2', name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(id: '3', name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: 'tags',
            remark: '',
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "tags"
                ]
            ]);
    }

    public function test_update_ok_when_form_data_correct() {
        $response   = $this->createDefaultFoodMenu('apple'); // id: 1
        $foodMenuId = $response->json('data.id');

        $response = $this->updateFoodMenu($foodMenuId, new FoodMenuRecord(
            start_at: '2021-12-22 20:10:00',
            foods: [
                new FoodMenuItemRecord(id: '1', name: 'apple', unit: 'per', quantity: 11),
                new FoodMenuItemRecord(id: '2', name: 'orange', unit: 'cup', quantity: 22),
                new FoodMenuItemRecord(name: 'cola', unit: 'can', quantity: 44),
            ],
            tags: ['test', 'fun', 'check'],
            remark: 'this is remark check',
        ));

        $response
            ->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "data" => [
                    "id"       => 1,
                    "start_at" => "2021-12-22 20:10:00",
                    "remark"   => "this is remark check",
                    "tags"     => ['test', 'fun', 'check'],
                    "foods"    => [
                        ["id" => 1, "name" => "apple", "unit" => "per", "quantity" => 11],
                        ["id" => 2, "name" => "orange", "unit" => "cup", "quantity" => 22],
                        ["id" => 4, "name" => "cola", "unit" => "can", "quantity" => 44],
                    ]
                ]
            ]);

        // 3 foods after updated, 4 record but 1 deleted in database
        $this->assertEquals(3, FoodMenuItem::where('user_id', $this->currentUser->id)->count());

        // 4 food names: apple, orange, water, cola
        // 4 food units: per, cup, can
        $this->assertEquals(4, FoodName::where('user_id', $this->currentUser->id)->count());
        $this->assertEquals(3, FoodUnit::where('user_id', $this->currentUser->id)->count());
    }

    // Helper
    protected function createDefaultFoodMenu() {
        return $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-22 20:04:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));
    }

}
