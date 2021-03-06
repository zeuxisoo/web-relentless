<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodUnit;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;

class FoodUnitControllerSharedTest extends ApiControllerTestCase {

    public function test_protected_routes() {
        $response = $this->post('/api/v1/food/unit/store');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/unit/list');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/unit/show/1');
        $response->assertStatus(401);

        $response = $this->post('/api/v1/food/unit/update');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/unit/search');
        $response->assertStatus(401);
    }

}
