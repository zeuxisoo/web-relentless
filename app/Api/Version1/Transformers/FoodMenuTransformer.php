<?php
namespace App\Api\Version1\Transformers;

use App\Api\Version1\Enums\TagCategory;
use App\Models\FoodMenu;
use League\Fractal\TransformerAbstract;

class FoodMenuTransformer extends TransformerAbstract {

    public function transform(FoodMenu $foodMenu) {
        return [
            'id'       => $foodMenu->id,
            'start_at' => $foodMenu->start_at,
            'remark'   => $foodMenu->remark,
            'tags'     => $foodMenu->tagsWithType(TagCategory::Food)->pluck('name'),
            'foods'    => $foodMenu->foods->map(function($food) {
                return (new FoodMenuItemTransformer())->transform($food);
            }),
        ];
    }

}
