<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodUnitStoreRequest;
use App\Api\Version1\Requests\FoodUnitUpdateRequest;
use App\Api\Version1\Services\FoodUnitService;
use App\Api\Version1\Transformers\FoodUnitTransformer;

class FoodUnitController extends ApiController {

    private FoodUnitService $foodUnitService;

    public function __construct(FoodUnitService $foodUnitService) {
        $this->foodUnitService = $foodUnitService;
    }

    public function store(FoodUnitStoreRequest $request) {
        $input    = $request->only('name');
        $foodUnit = $this->foodUnitService->create($input);

        return fractal($foodUnit, new FoodUnitTransformer());
    }

    public function update(FoodUnitUpdateRequest $request) {
        $input    = $request->only('id', 'name');
        $foodUnit = $this->foodUnitService->update($input);

        return fractal($foodUnit, new FoodUnitTransformer());
    }

}
