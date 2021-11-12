<?php
namespace App\Api\Version1\Services;

use App\Models\FoodName;
use Illuminate\Support\Facades\Auth;

class FoodNameService {

    public function create(array $data) {
        return FoodName::create($data);
    }

    public function update(array $data) {
        $foodName = $this->userFoodNameScope()->find($data['id']);

        $foodName->update([
            'name' => $data['name']
        ]);

        return $foodName;
    }

    public function find(array $data) {
        return $this->userFoodNameScope()->find($data['id']);
    }

    public function list(int $perPage = 8) {
        return $this->userFoodNameScope()->paginate($perPage);
    }

    public function search(string $keyword) {
        return $this->userFoodNameScope()
            ->where('name', 'LIKE', '%'.$keyword.'%')
            ->get();
    }

    // Shared methods
    protected function userFoodNameScope() {
        return FoodName::where('user_id', Auth::id());
    }

}
