<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodStoreRequest;
use App\Api\Version1\Services\FoodService;

class FoodController extends ApiController {

    private FoodService $foodService;

    public function __construct(FoodService $foodService) {
        $this->foodService = $foodService;
    }

    public function store(FoodStoreRequest $request) {
        $input = $request->only('name');
        $food  = $this->foodService->create($input);

        return $food;
    }

}
