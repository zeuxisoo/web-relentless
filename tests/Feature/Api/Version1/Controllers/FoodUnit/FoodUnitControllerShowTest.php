<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodUnit;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodUnitAction;

class FoodUnitControllerShowTest extends ApiControllerTestCase {

    use FoodUnitAction;

    public function test_show_failed_when_id_missing() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/show');

        $response
            ->assertStatus(200)
            ->assertSee("");
    }

    public function test_show_failed_when_id_not_exists() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/show/9999');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'ok',
                'errors' => [
                    'id'
                ],
            ]);
    }

    public function test_show_ok_when_id_exists() {
        $this->createFoodUnit("cup");

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/show/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data',
            ])
            ->assertJsonPath("data.name", "cup");
    }

    public function test_show_ok_when_id_exists_and_it_is_bottle() {
        $this->createFoodUnit("cup");
        $this->createFoodUnit("glass");
        $this->createFoodUnit("bottle");

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/show/3');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data',
            ])
            ->assertJsonPath("data.name", "bottle");
    }

}
