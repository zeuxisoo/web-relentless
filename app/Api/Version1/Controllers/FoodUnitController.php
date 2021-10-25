<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodUnitStoreRequest;
use App\Api\Version1\Services\FoodUnitService;

class FoodUnitController extends ApiController {

    private FoodUnitService $foodUnitService;

    public function __construct(FoodUnitService $foodUnitService) {
        $this->foodUnitService = $foodUnitService;
    }

    public function store(FoodUnitStoreRequest $request) {
        $input = $request->only('name');
        $food  = $this->foodUnitService->create($input);

        return $food;
    }

}
