<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodMenuNotePreviewRequest;
use App\Api\Version1\Transformers\FoodMenuNoteTransformer;
use App\Parsers\Food\Helper as FoodParserHelper;

class FoodMenuNoteController extends ApiController {

    public function preview(FoodMenuNotePreviewRequest $request) {
        $input = $request->only('text');

        $foods = FoodParserHelper::compile($input['text']);

        return fractal($foods, new FoodMenuNoteTransformer());
    }

}
