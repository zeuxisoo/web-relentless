<?php
namespace Tests\Feature\Api\Version1\Traits;

trait FoodNameAction {

    public function createFoodName(string $name = '') {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/name/store', [
                "name" => $name
            ]);
    }

    public function updateFoodName(string $id, string $name) {
        return $this
            ->withAuthorization()
            ->post('/api/v1/food/name/update', [
                "id"   => $id,
                "name" => $name
            ]);
    }

    public function createFoodNames(string ...$names) {
        foreach($names as $name) {
            $this->createFoodName($name);
        }
    }

}
