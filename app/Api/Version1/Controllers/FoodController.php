<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodStoreRequest;

class FoodController extends ApiController {

    public function store(FoodStoreRequest $request) {
        $input = $request->only('name');

        return $input;
    }

}
