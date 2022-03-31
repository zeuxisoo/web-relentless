<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;

class FoodMenuControllerNoteStoreTest extends ApiControllerTestCase {

    use FoodMenuAction;

    // No need to test failed case because the same test case is used in FoodMenuControllerNotePreviewTest
    // So, only test the ok case
    public function test_note_store_ok_when_text_valid() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/note/preview', [
                'text' => <<<NOW
                // 測試this is a test @!@#$

                2022-03-28 20:05 #杯 #測試 #tag #123 #tag-123-測
                    香蕉 @ 1條
                    Banana @ 0.5 slice
                    > this is a test
                    測試

                2022-03-28 {
                    09:00 #水 #water
                    水 @ 1杯
                    Cola @ 1.5L

                    09:01
                    麵@1碗
                    麵@2碗
                    > 唔好食既
                }
                NOW,
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonCount(3, "data")
            ->assertJson([
                "ok" => true,
                "data" => [
                    [
                        "date"  => "2022-03-28",
                        "time"  => "20:05",
                        "tags"  => ["杯", "測試", "tag", "123", "tag-123-測"],
                        "foods" => [
                            ["name" => "香蕉", "quantity" => 1, "unit" => "條",],
                            ["name" => "Banana", "quantity" => 0.5, "unit" => "slice",],
                        ],
                        "remark" => "this is a test\n    測試",
                    ],
                    [
                        "date"  => "2022-03-28",
                        "time"  => "09:00",
                        "tags"  => ["水", "water"],
                        "foods" => [
                            ["name" => "水", "quantity" => 1, "unit" => "杯",],
                            ["name" => "Cola", "quantity" => 1.5, "unit" => "L",],
                        ],
                        "remark" => "",
                    ],
                    [
                        "date"  => "2022-03-28",
                        "time"  => "09:01",
                        "tags"  => [],
                        "foods" => [
                            ["name" => "麵", "quantity" => 1, "unit" => "碗",],
                            ["name" => "麵", "quantity" => 2, "unit" => "碗",],
                        ],
                        "remark" => "唔好食既",
                    ],
                ]
            ]);

    }

}
