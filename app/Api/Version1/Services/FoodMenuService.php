<?php
namespace App\Api\Version1\Services;

use App\Api\Version1\Enums\TagCategory;
use App\Models\FoodMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoodMenuService {

    public function __construct(
        public FoodMenuItemService $foodMenuItemService
    ) {}

    public function create(array $data) {
        $foodMenu = DB::transaction(function() use ($data) {
            // Food menu
            $foodMenu = FoodMenu::create([
                'start_at' => $data['start_at'],
                'remark'   => $data['remark'],
            ]);

            // Food menu tags
            $foodMenu->attachTags($data['tags'], TagCategory::Food);

            // Food menu items
            $this->foodMenuItemService->insert($data['foods'], $foodMenu);

            // Load the relationship first
            $foodMenu->load('foodItems.foodName', 'foodItems.foodUnit');

            return $foodMenu;
        });

        return $foodMenu;
    }

    public function find(array $data) {
        return FoodMenu::with('foodItems.foodName', 'foodItems.foodUnit')
            ->where('user_id', Auth::id())
            ->find($data['id']);
    }

}
