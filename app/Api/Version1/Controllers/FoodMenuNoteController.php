<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodMenuNotePreviewRequest;
use App\Api\Version1\Requests\FoodMenuNoteStoreRequest;
use App\Api\Version1\Services\FoodMenuNoteService;
use App\Api\Version1\Transformers\FoodMenuNoteTransformer;
use App\Api\Version1\Transformers\FoodMenuTransformer;
use App\Parsers\Food\Exceptions\GeneratorException;
use App\Parsers\Food\Exceptions\LexerException;
use App\Parsers\Food\Exceptions\ParserException;
use App\Parsers\Food\Helper as FoodParserHelper;
use Illuminate\Http\JsonResponse;
use Spatie\Fractal\Fractal;

class FoodMenuNoteController extends ApiController {

    public function __construct(
        public FoodMenuNoteService $foodMenuNoteService,
    ) {}

    public function preview(FoodMenuNotePreviewRequest $request): JsonResponse|Fractal {
        $input = $request->only('text');
        $foods = $this->parseFoodsOrErrors($input['text']);

        if ($foods instanceof JsonResponse) {
            return $foods;
        }

        return fractal($foods, new FoodMenuNoteTransformer());
    }

    public function store(FoodMenuNoteStoreRequest $request): JsonResponse|Fractal {
        $input = $request->only('text');
        $foods = $this->parseFoodsOrErrors($input['text']);

        if ($foods instanceof JsonResponse) {
            return $foods;
        }

        $foodMenus = $this->foodMenuNoteService->create($foods);

        return fractal($foodMenus, new FoodMenuTransformer());
    }

    // Helpers
    protected function parseFoodsOrErrors(string $text): array|JsonResponse {
        try {
            return FoodParserHelper::compile($text);
        }catch(LexerException|ParserException|GeneratorException $e) {
            return $this->respondWithErrors([
                "text" => [$e->getMessage()] // not translate because it's dynamic generate
            ]);
        }
    }

}
