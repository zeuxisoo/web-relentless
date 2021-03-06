<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodName;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodNameAction;

class FoodNameControllerUpdateTest extends ApiControllerTestCase {

    use FoodNameAction;

    public function test_update_failed_when_id_empty() {
        $response = $this->updateFoodName('', 'apple-updated');

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
        $response = $this->createFoodName('apple'); // id: 1
        $response = $this->updateFoodName(2, 'apple-updated');

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
        $response   = $this->createFoodName('apple');    // id: 1
        $foodNameId = $response->json('data.id');

        $response = $this->updateFoodName($foodNameId, '');

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
        $response = $this->createFoodName('apple'); // id: 1
        $foodNameId = $response->json('data.id');

        $response = $this->updateFoodName($foodNameId, 'apple');

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
        $response   = $this->createFoodName('apple'); // id: 1
        $foodNameId = $response->json('data.id');

        $response = $this->updateFoodName($foodNameId, 'apple-updated');

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
            ->assertJsonPath("data.name", "apple-updated");
    }

}
