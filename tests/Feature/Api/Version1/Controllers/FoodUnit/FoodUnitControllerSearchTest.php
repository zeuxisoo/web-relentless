<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodUnitcreateFoodUnit;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodUnitAction;

class FoodUnitControllerSearchTest extends ApiControllerTestCase {

    use FoodUnitAction;

    public function test_search_failed_when_keyword_missing() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/search');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "keyword"
                ]
            ]);
    }

    public function test_search_ok_when_keyword_exists() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/unit/search?'.http_build_query([
                'keyword' => 'not-exists-name'
            ]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data",
            ])
            ->assertJsonCount(0, "data");
    }

    public function test_search_ok_when_query_query_correct() {
        $this->createFoodUnit("cup");
        $this->createFoodUnit("glass");
        $this->createFoodUnit("bottle");

        $testResponse = function(string $keyword, int $expectedTotal) {
            $this
                ->withAuthorization()
                ->get('/api/v1/food/unit/search?'.http_build_query([
                    'keyword' => $keyword
                ]))
                ->assertStatus(200)
                ->assertJsonStructure([
                    "ok",
                    "data",
                ])
                ->assertJsonCount($expectedTotal, "data");
        };

        $testResponse("cup", 1);
        $testResponse("glass", 1);
        $testResponse("bottle", 1);
    }

}
