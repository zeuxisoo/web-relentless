<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;

class FoodMenuControllerNoteTest extends ApiControllerTestCase {

    use FoodMenuAction;

    public function test_note_preview_failed_when_text_missing() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/note/preview');

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "text"
                ]
            ]);
    }

    public function test_note_preview_failed_when_text_stop_lexer() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/note/preview', [
                'text' => <<<NOW
                2022-03-28 20:05 #tag
                    name @ 1 unit
                    error @ +1 plus // unknown +
                NOW,
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "text"
                ],
            ])
            ->assertSee('LexerError')
            ->assertSee('+');
    }

    public function test_note_preview_failed_when_text_stop_parse() {
        $response = $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/note/preview', [
                'text' => <<<NOW
                2022-03-28 20:05 #tag
                    name @ 1 unit

                2022-03-28 {
                    // must contain time, food and so on
                }
                NOW,
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "ok",
                "errors" => [
                    "text"
                ],
            ])
            ->assertSee('ParseError');
    }

    public function test_note_preview_ok_when_text_valid() {
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
            ->assertJsonStructure([
                "ok",
                "data",
            ])
            ->assertJsonPath("ok", true)
            ->assertJsonCount(3, "data")
            ->assertJsonFragment([
                "date" => "2022-03-28",
                "time" => "20:05",
                "tags" => ["杯", "測試", "tag", "123", "tag-123-測"],
                "foods" => [
                    [
                        "name" => "香蕉",
                        "quantity" => 1,
                        "unit" => "條",
                    ],
                    [
                        "name" => "Banana",
                        "quantity" => 0.5,
                        "unit" => "slice",
                    ],
                ],
                "remark" => <<<NOW
                this is a test
                    測試
                NOW,
            ])
            ->assertJsonPath("data.1.remark", "")
            ->assertJsonPath("data.2.tags", []);
    }

}
