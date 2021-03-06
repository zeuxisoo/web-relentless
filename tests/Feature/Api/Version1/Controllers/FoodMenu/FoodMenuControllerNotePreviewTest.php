<?php
namespace Tests\Feature\Api\Version1\Controllers\FoodMenu;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;
use Tests\Feature\Api\Version1\Traits\FoodMenuAction;

class FoodMenuControllerNotePreviewTest extends ApiControllerTestCase {

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
                // ??????this is a test @!@#$

                2022-03-28 20:05 #??? #?????? #tag #123 #tag-123-???
                    ?????? @ 1???
                    Banana @ 0.5 slice
                    > this is a test
                    ??????

                2022-03-28 {
                    09:00 #??? #water
                    ??? @ 1???
                    Cola @ 1.5L

                    09:01
                        ???@1???
                        ???@2???
                        > ????????????
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
                "tags" => ["???", "??????", "tag", "123", "tag-123-???"],
                "foods" => [
                    [
                        "name" => "??????",
                        "quantity" => 1,
                        "unit" => "???",
                    ],
                    [
                        "name" => "Banana",
                        "quantity" => 0.5,
                        "unit" => "slice",
                    ],
                ],
                "remark" => <<<NOW
                this is a test
                    ??????
                NOW,
            ])
            ->assertJsonPath("data.1.remark", "")
            ->assertJsonPath("data.2.tags", []);
    }

}
