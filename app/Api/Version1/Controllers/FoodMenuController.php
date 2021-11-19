<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodMenuStoreRequest;

class FoodMenuController extends ApiController {

    public function store(FoodMenuStoreRequest $request) {
        // TODO: store the food names and units into menu record
        return "WIP food menu store method";
    }

}
