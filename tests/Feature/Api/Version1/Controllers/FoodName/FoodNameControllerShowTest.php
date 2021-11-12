<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodName;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodNameAction;

class FoodNameControllerShowTest extends ApiControllerTestCase {

    use FoodNameAction;

    public function test_show_failed_when_id_missing() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/show');

        $response
            ->assertStatus(200)
            ->assertSee("");
    }

    public function test_show_failed_when_id_not_eixsts() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/show/9999');

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
        $this->createFoodName("apple");

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/show/1');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data',
            ])
            ->assertJsonPath("data.name", "apple");
    }

    public function test_show_ok_when_id_exists_and_it_is_orange() {
        $this->createFoodName("apple");
        $this->createFoodName("apple pie");
        $this->createFoodName("orange");

        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/show/3');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'ok',
                'data',
            ])
            ->assertJsonPath("data.name", "orange");
    }

}
