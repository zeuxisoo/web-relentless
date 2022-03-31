<?php
namespace App\Api\Version1\Services;

use App\Models\FoodUnit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FoodUnitService {

    public function create(array $data): Model {
        return FoodUnit::create($data);
    }

    public function list(int $perPage = 8): LengthAwarePaginator {
        return $this->userFoodUnitScope()->paginate($perPage);
    }

    public function find(array $data): Model|Collection|static|null {
        return $this->userFoodUnitScope()->find($data['id']);
    }

    public function update(array $data): Model|Collection|static|null {
        $foodUnit = $this->userFoodUnitScope()->find($data['id']);

        $foodUnit->update([
            'name' => $data['name']
        ]);

        return $foodUnit;
    }

    public function search(string $keyword): Collection {
        return $this->userFoodUnitScope()
            ->where('name', 'LIKE', '%'.$keyword.'%')
            ->get();
    }

    // Shared methods
    protected function userFoodUnitScope(): Builder {
        return FoodUnit::where('user_id', Auth::id());
    }

}
