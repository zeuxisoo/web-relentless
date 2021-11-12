<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodName;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodNameAction;

class FoodNameControllerSearchTest extends ApiControllerTestCase {

    use FoodNameAction;

    public function test_search_failed_when_keyword_missing() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/name/search');

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
            ->get('/api/v1/food/name/search?'.http_build_query([
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
        $this->createFoodName("apple");
        $this->createFoodName("apple pie");
        $this->createFoodName("orange");

        $testResponse = function(string $keyword, int $expectedTotal) {
            $this
                ->withAuthorization()
                ->get('/api/v1/food/name/search?'.http_build_query([
                    'keyword' => $keyword
                ]))
                ->assertStatus(200)
                ->assertJsonStructure([
                    "ok",
                    "data",
                ])
                ->assertJsonCount($expectedTotal, "data");
        };

        $testResponse("apple", 2);
        $testResponse("orange", 1);
        $testResponse("pie", 1);
    }

}
