<?php
namespace App\Api\Version1\Transformers;

use App\Models\FoodName;
use League\Fractal\TransformerAbstract;

class FoodNameTransformer extends TransformerAbstract {

    public function transform(FoodName $foodName) {
        return [
            'id'   => $foodName->id,
            'name' => $foodName->name,
        ];
    }

}
