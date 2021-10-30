<?php
namespace Tests\Feature\Api\Version1\Traits;

trait FoodUnitAction {

    public function createFoodUnit(string $name = '') {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/unit/store', [
                "name" => $name
            ]);
    }

    public function updateFoodUnit(string $id, string $name) {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/unit/update', [
                "id"   => $id,
                "name" => $name
            ]);
    }

}
