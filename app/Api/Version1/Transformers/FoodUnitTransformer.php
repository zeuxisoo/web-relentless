<?php
namespace App\Api\Version1\Transformers;

use App\Models\FoodUnit;
use League\Fractal\TransformerAbstract;

class FoodUnitTransformer extends TransformerAbstract {

    public function transform(FoodUnit $foodUnit): array {
        return [
            'id'   => $foodUnit->id,
            'name' => $foodUnit->name,
        ];
    }

}
