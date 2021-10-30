<?php
namespace Tests\Feature\Api\Version1\Controllers;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Controllers\Traits\FoodName\StoreTestable;
use Tests\Feature\Api\Version1\Controllers\Traits\FoodName\UpdateTestable;

class FoodNameControllerTest extends ApiControllerTestCase {

    use StoreTestable;
    use UpdateTestable;

    // Protected routes test
    public function test_protected_routes() {
        $response = $this->post('/api/v1/food/name/store');
        $response->assertStatus(401);
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
