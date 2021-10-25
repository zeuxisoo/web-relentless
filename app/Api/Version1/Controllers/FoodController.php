<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodStoreRequest;
use App\Models\Food;

class FoodController extends ApiController {

    public function store(FoodStoreRequest $request) {
        $input = $request->only('name');

        $food = Food::create($input);

        return $food;
    }

}
