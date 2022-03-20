<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Records\FoodMenuItemRecord;
use Tests\Feature\Api\Version1\Records\FoodMenuRecord;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;

class FoodMenuControllerSearchTest extends ApiControllerTestCase {

    use FoodMenuAction;

    public function test_search_failed_when_keyword_missing() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/food/menu/search');

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
            ->get('/api/v1/food/menu/search?'.http_build_query([
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
        $this->createFoodMenu(new FoodMenuRecord(
            start_at: '2022-01-17 20:20:00',
            foods: [
                new FoodMenuItemRecord(name: 'apple', unit: 'per', quantity: 111),
                new FoodMenuItemRecord(name: 'orange', unit: 'per', quantity: 222),
                new FoodMenuItemRecord(name: 'water', unit: 'cup', quantity: 3333),
            ],
            tags: ['test', 'dinner', 'satisfy'],
            remark: 'this is a test 這是一個測試',
        ));

        $testResponse = function(string $keyword, int $expectedTotal) {
            $this
                ->withAuthorization()
                ->get('/api/v1/food/menu/search?'.http_build_query([
                    'keyword' => $keyword
                ]))
                ->assertStatus(200)
                ->assertJsonStructure([
                    "ok",
                    "data",
                ])
                ->assertJsonCount($expectedTotal, "data");
        };

        // foods
        $testResponse("apple", 1);
        $testResponse("watermelon", 0);

        // units
        $testResponse("cup", 1);
        $testResponse("pie", 0);

        // tags
        $testResponse("dinner", 1);
        $testResponse("fun", 0);

        // quantity
        $testResponse("333", 1);
        $testResponse("444", 0);

        // remark
        $testResponse("一個", 1);
        $testResponse("不存在", 0);
    }

}
