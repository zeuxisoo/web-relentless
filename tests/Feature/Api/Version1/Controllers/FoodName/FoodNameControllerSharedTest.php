<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodName;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;

class FoodNameControllerSharedTest extends ApiControllerTestCase {

    public function test_protected_routes() {
        $response = $this->post('/api/v1/food/name/store');
        $response->assertStatus(401);
    }

}
