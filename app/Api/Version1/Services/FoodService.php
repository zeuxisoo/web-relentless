<?php
namespace App\Api\Version1\Services;

use App\Models\Food;

class FoodService {

    public function create($data) {
        return Food::create($data);
    }

}
