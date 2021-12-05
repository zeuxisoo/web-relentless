<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use App\Models\FoodMenuItem;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Records\FoodMenuItemRecord;
use Tests\Feature\Api\Version1\Records\FoodMenuRecord;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;
use Tests\Feature\Api\Version1\Traits\FoodNameAction;
use Tests\Feature\Api\Version1\Traits\FoodUnitAction;

class FoodMenuControllerStoreTest extends ApiControllerTestCase {

    use FoodNameAction, FoodUnitAction;
    use FoodMenuAction;

    public function test_store_failed_when_form_data_are_empty() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/store');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "errors" => [
                    "start_at"
                ]
            ]);
    }

    public function test_store_failed_when_start_at_is_empty() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: ''
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

    public function test_store_failed_when_start_at_invalid_format() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-13-32 25:41:00'
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

    public function test_store_failed_when_foods_is_empty() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: []
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

    public function test_store_failed_when_foods_is_not_array() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: ''
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

    public function test_store_failed_tags_is_empty() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: [
                new FoodMenuItemRecord('')
            ],
            tags: []
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

    public function test_store_failed_tags_is_not_array() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: [
                new FoodMenuItemRecord('')
            ],
            tags: ''
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

    public function test_store_failed_foods_name_is_not_empty() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: [
                new FoodMenuItemRecord('')
            ],
            tags: ['test']
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "foods.0.name"
                ]
            ]);
    }

    public function test_store_failed_foods_unit_is_not_empty() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: '')
            ],
            tags: ['test']
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "foods.0.unit"
                ]
            ]);
    }

    public function test_store_failed_foods_quantity_is_not_empty() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: '')
            ],
            tags: ['test']
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "foods.0.quantity"
                ]
            ]);
    }

    public function test_store_failed_foods_quantity_must_be_integer() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: 'a')
            ],
            tags: ['test']
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "foods.0.quantity"
                ]
            ]);
    }

    public function test_store_failed_foods_quantity_must_gether_than_zero() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 19:42:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: -1)
            ],
            tags: ['test']
        ));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "foods.0.quantity"
                ]
            ]);
    }

    public function test_store_ok_when_food_name_is_not_duplicate_created() {
        $this->createFoodNames('apple', 'banana', 'cola', 'water');

        $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 20:06:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        // Ensure the food name do not create twice
        // final: [apple, banana, cola, water, orange]
        $foodNameBuilder = FoodName::where('user_id', $this->currentUser->id);

        $foodNameId = function($name) use ($foodNameBuilder) {
            return (clone $foodNameBuilder)
                ->where('name', $name)
                ->first()
                ->id;
        };

        $this->assertEquals(1, $foodNameId('apple'));
        $this->assertEquals(5, $foodNameId('orange'));
        $this->assertEquals(4, $foodNameId('water'));

        $this->assertEquals(5, $foodNameBuilder->count());

        // Ensure the food name id is linked and correct in food menu table
        // structure:
        // 1. apple, per
        // 2. orange, per
        // 3. water, cup
        $countFoodNameIdInFoodMenuItem = function($foodNameId) {
            return FoodMenuItem::where('user_id', $this->currentUser->id)
                ->where('food_name_id', $foodNameId)
                ->count();
        };

        $this->assertEquals(1, $countFoodNameIdInFoodMenuItem(1));
        $this->assertEquals(1, $countFoodNameIdInFoodMenuItem(5));
        $this->assertEquals(1, $countFoodNameIdInFoodMenuItem(4));
    }

    public function test_store_ok_when_food_unit_is_not_duplicate_created() {
        $this->createFoodNames('per', 'cup');

        $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 20:06:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        // Ensure the food unit do not create twice
        // final: [per, cup]
        $foodUnitBuilder = FoodUnit::where('user_id', $this->currentUser->id);

        $foodUnitId = function($name) use ($foodUnitBuilder) {
            return (clone $foodUnitBuilder)
                ->where('name', $name)
                ->first()
                ->id;
        };

        $this->assertEquals(1, $foodUnitId('per'));
        $this->assertEquals(2, $foodUnitId('cup'));

        $this->assertEquals(2, $foodUnitBuilder->count());

        // Ensure the food unit id is linked and correct in food menu table
        // structure:
        // 1. apple, per
        // 2. orange, per
        // 3. water, cup
        $countFoodUnitIdInFoodMenuItem = function($foodUnitId) {
            return FoodMenuItem::where('user_id', $this->currentUser->id)
                ->where('food_unit_id', $foodUnitId)
                ->count();
        };

        $this->assertEquals(2, $countFoodUnitIdInFoodMenuItem(1));
        $this->assertEquals(1, $countFoodUnitIdInFoodMenuItem(2));
    }

    public function test_store_ok_when_form_data_correct() {
        $response = $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2021-12-05 20:06:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: 1),
                new FoodMenuItemRecord(name: 'orange', unit: 'per', quantity: 2),
                new FoodMenuItemRecord(name: 'water', unit: 'cup', quantity: 3),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: '',
        ));

        $response
            ->assertStatus(200)
            ->assertJson([
                "ok" => true,
                "data" => [
                    "start_at" => '2021-12-05 20:06:00',
                    "foods" => [
                        ["id" => 1, "name" => "apple", "unit" => "per", "quantity" => "1"],
                        ["id" => 2, "name" => "orange", "unit" => "per", "quantity" => "2"],
                        ["id" => 3, "name" => "water", "unit" => "cup", "quantity" => "3"],
                    ],
                    "tags" => ["test", "dinner", "satisfy"],
                    "remark" => "",
                ]
            ]);
    }

}
