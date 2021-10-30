<?php
namespace Tests\Feature\Api\Version1\Controllers;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;

class FoodNameControllerTest extends ApiControllerTestCase {

    // Protected routes test
    public function test_protected_routes() {
        $response = $this->post('/api/v1/food/name/store');
        $response->assertStatus(401);
    }

    // api.food.name.store
    public function test_store_failed_when_form_data_are_empty() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/name/store');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "errors" => [
                    "name"
                ]
            ]);
    }

    public function test_store_failed_when_name_is_empty() {
        $response = $this->createFoodName();

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "name"
                ]
            ]);
    }

    public function test_store_failed_when_name_already_exists() {
        $response = $this->createFoodName('apple'); // first : ok
        $response = $this->createFoodName('apple'); // second: failed

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "name"
                ]
            ]);
    }

    public function test_store_ok_when_form_data_correct() {
        $response = $this->createFoodName('apple');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data" => [
                    "id",
                    "name",
                ]
            ]);
    }

    // api.food.name.update
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

    // Helper methods
    public function createFoodName(string $name = '') {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/name/store', [
                "name" => $name
            ]);
    }

    public function updateFoodName(string $id, string $name) {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/name/update', [
                "id"   => $id,
                "name" => $name
            ]);
    }

}
