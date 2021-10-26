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
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/name/store', [
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
            ->post('/api/v1/food/name/store', [
                "name" => "apple"
            ]);

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

}
