<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;

class FoodMenuControllerSharedTest extends ApiControllerTestCase {

    public function test_protected_routes() {
        $response = $this->post('/api/v1/food/menu/store');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/menu/list');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/menu/show/1');
        $response->assertStatus(401);

        $response = $this->post('/api/v1/food/menu/update');
        $response->assertStatus(401);

        $response = $this->get('/api/v1/food/menu/search');
        $response->assertStatus(401);

        $response = $this->post('/api/v1/food/menu/note/preview');
        $response->assertStatus(401);
    }

}
