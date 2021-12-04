<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodMenuShowRequest;
use App\Api\Version1\Requests\FoodMenuStoreRequest;
use App\Api\Version1\Services\FoodMenuService;
use App\Api\Version1\Transformers\FoodMenuTransformer;

class FoodMenuController extends ApiController {

    public function __construct(
        public FoodMenuService $foodMenuService
    ) {}

    public function store(FoodMenuStoreRequest $request) {
        $input    = $request->only('start_at', 'foods', 'tags', 'remark');
        $foodMenu = $this->foodMenuService->create($input);

        return fractal($foodMenu, new FoodMenuTransformer());
    }

    public function show(FoodMenuShowRequest $request) {
        $input    = $request->only('id');
        $foodMenu = $this->foodMenuService->find($input);

        return fractal($foodMenu, new FoodMenuTransformer());
    }

}
