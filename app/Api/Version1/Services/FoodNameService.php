<?php
namespace App\Api\Version1\Services;

use App\Models\FoodName;

class FoodNameService {

    public function create($data) {
        return FoodName::create($data);
    }

}
