<?php
namespace App\Api\Version1\Services;

use App\Models\FoodUnit;
use Illuminate\Support\Facades\Auth;

class FoodUnitService {

    public function create(array $data) {
        return FoodUnit::create($data);
    }

    public function list(int $perPage = 8) {
        return $this->userFoodUnitScope()->paginate($perPage);
    }

    public function find(array $data) {
        return $this->userFoodUnitScope()->find($data['id']);
    }

    public function update(array $data) {
        $foodUnit = $this->userFoodUnitScope()->find($data['id']);

        $foodUnit->update([
            'name' => $data['name']
        ]);

        return $foodUnit;
    }

    public function search(string $keyword) {
        return $this->userFoodUnitScope()
            ->where('name', 'LIKE', '%'.$keyword.'%')
            ->get();
    }

    // Shared methods
    protected function userFoodUnitScope() {
        return FoodUnit::where('user_id', Auth::id());
    }

}
