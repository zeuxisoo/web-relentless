<?php
namespace App\Api\Version1\Transformers;

use App\Models\FoodMenuItem;
use League\Fractal\TransformerAbstract;

class FoodMenuItemTransformer extends TransformerAbstract {

    public function transform(FoodMenuItem $foodMenuItem) {
        return [
            'id'       => $foodMenuItem->id,
            'name'     => $foodMenuItem->name,
            'unit'     => $foodMenuItem->unit,
            'quantity' => $foodMenuItem->quantity,
        ];
    }

}
