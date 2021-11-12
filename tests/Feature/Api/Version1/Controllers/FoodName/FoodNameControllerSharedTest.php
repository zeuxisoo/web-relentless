<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodName;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;

class FoodNameControllerSharedTest extends ApiControllerTestCase {

    public function test_protected_routes() {
        $response = $this->post('/api/v1/food/name/store');
        $response->assertStatus(401);

        $response = $this->post('/api/v1/food/name/update');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/name/list');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/name/search');
        $response->assertStatus(401);
    }

}
