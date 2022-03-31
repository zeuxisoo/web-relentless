<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodMenuNotePreviewRequest;
use App\Api\Version1\Transformers\FoodMenuNoteTransformer;
use App\Parsers\Food\Exceptions\GeneratorException;
use App\Parsers\Food\Exceptions\LexerException;
use App\Parsers\Food\Exceptions\ParserException;
use App\Parsers\Food\Helper as FoodParserHelper;

class FoodMenuNoteController extends ApiController {

    public function preview(FoodMenuNotePreviewRequest $request) {
        $input = $request->only('text');
        $foods = [];

        try {
            $foods = FoodParserHelper::compile($input['text']);
        }catch(LexerException|ParserException|GeneratorException $e) {
            return $this->respondWithErrors([
                "text" => [$e->getMessage()] // not translate because it's dynamic generate
            ]);
        }

        return fractal($foods, new FoodMenuNoteTransformer());
    }

}
