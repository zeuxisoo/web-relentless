<?php
namespace Tests\Feature\Api\Version1\Traits;

use Tests\Feature\Api\Version1\Records\FoodMenuRecord;

trait FoodMenuAction {

    public function createFoodMenu(FoodMenuRecord $foodMenuRecord) {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/store', $foodMenuRecord->toArray());
    }

}
