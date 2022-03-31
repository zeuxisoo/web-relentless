<?php
namespace App\Api\Version1\Transformers;

use App\Models\FoodMenuItem;
use League\Fractal\TransformerAbstract;

class FoodMenuItemTransformer extends TransformerAbstract {

    public function transform(FoodMenuItem $foodMenuItem): array {
        return [
            'id'       => $foodMenuItem->id,
            'name'     => $foodMenuItem->name->name ?? $foodMenuItem->name, // From model relation or left join
            'unit'     => $foodMenuItem->unit->name ?? $foodMenuItem->unit, //
            'quantity' => $foodMenuItem->quantity,
        ];
    }

}
