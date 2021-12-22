<?php
namespace Tests\Feature\Api\Version1\Traits;

use Tests\Feature\Api\Version1\Records\FoodMenuRecord;

trait FoodMenuAction {

    public function createFoodMenu(FoodMenuRecord $foodMenuRecord) {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/store', $foodMenuRecord->toArray());
    }

    public function updateFoodMenu(string $id, ?FoodMenuRecord $foodMenuRecord = null) {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/menu/update', array_merge(
                ['id' => $id],
                $foodMenuRecord === null ? [] : $foodMenuRecord->toArray(),
            ));

    }
}
