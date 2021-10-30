<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodUnit;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodUnitAction;

class FoodUnitControllerUpdateTest extends ApiControllerTestCase {

    use FoodUnitAction;

    public function test_update_failed_when_id_empty() {
        $response = $this->updateFoodUnit('', 'cup-updated');

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
        $response = $this->createFoodUnit('cup'); // id: 1
        $response = $this->updateFoodUnit(2, 'cup-updated');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "id"
                ]
            ]);
    }

    public function test_update_failed_when_name_is_empty() {
        $response   = $this->createFoodUnit('cup');    // id: 1
        $foodNameId = $response->json('data.id');

        $response = $this->updateFoodUnit($foodNameId, '');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "name"
                ]
            ]);
    }

    public function test_update_failed_when_name_already_exists() {
        $response = $this->createFoodUnit('cup'); // id: 1
        $foodNameId = $response->json('data.id');

        $response = $this->updateFoodUnit($foodNameId, 'cup');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "name"
                ]
            ]);
    }

    public function test_update_ok_when_form_data_correct() {
        $response   = $this->createFoodUnit('cup'); // id: 1
        $foodNameId = $response->json('data.id');

        $response = $this->updateFoodUnit($foodNameId, 'cup-updated');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data" => [
                    "id",
                    "name"
                ]
            ])
            ->assertJsonPath("data.id", 1)
            ->assertJsonPath("data.name", "cup-updated");
    }

}
