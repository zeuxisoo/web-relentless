<?php
namespace App\Api\Version1\Services;

use App\Models\FoodUnit;

class FoodUnitService {

    public function create(array $data) {
        return FoodUnit::create($data);
    }

}
