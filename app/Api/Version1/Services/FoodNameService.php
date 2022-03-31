<?php
namespace App\Api\Version1\Services;

use App\Models\FoodName;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FoodNameService {

    public function create(array $data): Model {
        return FoodName::create($data);
    }

    public function list(int $perPage = 8): LengthAwarePaginator {
        return $this->userFoodNameScope()->paginate($perPage);
    }

    public function find(array $data): Model|Collection|static|null {
        return $this->userFoodNameScope()->find($data['id']);
    }

    public function update(array $data): Model|Collection|static|null {
        $foodName = $this->userFoodNameScope()->find($data['id']);

        $foodName->update([
            'name' => $data['name']
        ]);

        return $foodName;
    }

    public function search(string $keyword): Collection {
        return $this->userFoodNameScope()
            ->where('name', 'LIKE', '%'.$keyword.'%')
            ->get();
    }

    // Shared methods
    protected function userFoodNameScope(): Builder {
        return FoodName::where('user_id', Auth::id());
    }

}
