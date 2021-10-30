<?php
namespace App\Api\Version1\Services;

use App\Models\FoodName;
use Illuminate\Support\Facades\Auth;

class FoodNameService {

    public function create(array $data) {
        return FoodName::create($data);
    }

    public function update(array $data) {
        $foodName = FoodName::where('user_id', Auth::id())->find($data['id']);

        $foodName->update([
            'name' => $data['name']
        ]);

        return $foodName;
    }

}
