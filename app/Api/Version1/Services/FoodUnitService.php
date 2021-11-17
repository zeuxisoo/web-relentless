<?php
namespace App\Api\Version1\Services;

use App\Models\FoodUnit;
use Illuminate\Support\Facades\Auth;

class FoodUnitService {

    public function create(array $data) {
        return FoodUnit::create($data);
    }

    public function list(int $perPage = 8) {
        return FoodUnit::where('user_id', Auth::id())->paginate($perPage);
    }

    public function find(array $data) {
        return FoodUnit::where('user_id', Auth::id())->find($data['id']);
    }

    public function update(array $data) {
        $foodUnit = FoodUnit::where('user_id', Auth::id())->find($data['id']);

        $foodUnit->update([
            'name' => $data['name']
        ]);

        return $foodUnit;
    }

}
