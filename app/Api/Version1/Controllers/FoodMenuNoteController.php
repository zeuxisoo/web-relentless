<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodMenuNotePreviewRequest;
use App\Api\Version1\Transformers\FoodMenuNoteTransformer;
use App\Parsers\Food\{
    Lexer,
    Parser,
    Traverser,
    Generator,
};

class FoodMenuNoteController extends ApiController {

    public function preview(FoodMenuNotePreviewRequest $request) {
        $input = $request->only('text');

        $lexer     = new Lexer($input['text']);
        $parser    = new Parser($lexer);
        $traverser = new Traverser($parser);
        $generator = new Generator($traverser);

        $foods = $generator->generate();

        return fractal($foods, new FoodMenuNoteTransformer());
    }

}
