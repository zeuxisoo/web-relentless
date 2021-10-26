<?php
namespace App\Api\Version1\Services;

use App\Models\FoodName;

class FoodNameService {

    public function create(array $data) {
        return FoodName::create($data);
    }

}
