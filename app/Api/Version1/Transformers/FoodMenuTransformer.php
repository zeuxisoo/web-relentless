<?php
namespace App\Api\Version1\Transformers;

use App\Models\FoodMenu;
use League\Fractal\TransformerAbstract;

class FoodMenuTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'foods',
    ];

    public function transform(FoodMenu $foodMenu) {
        return [
            'id'       => $foodMenu->id,
            'start_at' => $foodMenu->start_at,
            'remark'   => $foodMenu->remark,
            'tags'     => $foodMenu->tagsWithType('food')->pluck('name'),
        ];
    }

    public function includeFoods(FoodMenu $foodMenu) {
        return $this->collection($foodMenu->foodItems, new FoodMenuItemTransformer());
    }

}