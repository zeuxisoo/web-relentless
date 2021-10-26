<?php
namespace Tests\Feature\Api\Version1\Controllers;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;

class FoodUnitControllerTest extends ApiControllerTestCase {

    // Protected routes test
    public function test_protected_routes() {
        $response = $this->post('/api/v1/food/unit/store');
        $response->assertStatus(401);
    }

    // api.food.unit.store
    public function test_store_failed_when_form_data_are_empty() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/unit/store');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "errors" => [
                    "name"
                ]
            ]);
    }

    public function test_store_failed_when_name_is_empty() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/unit/store', [
                "name" => ""
            ]);

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
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/unit/store', [
                "name" => "cup"
            ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                "id",
                "user_id",
                "name",
                "created_at",
                "updated_at"
            ]);
    }

}
