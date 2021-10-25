<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodNameStoreRequest;
use App\Api\Version1\Services\FoodNameService;

class FoodNameController extends ApiController {

    private FoodNameService $foodNameService;

    public function __construct(FoodNameService $foodNameService) {
        $this->foodNameService = $foodNameService;
    }

    public function store(FoodNameStoreRequest $request) {
        $input = $request->only('name');
        $food  = $this->foodNameService->create($input);

        return $food;
    }

}
