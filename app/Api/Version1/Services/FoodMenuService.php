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

            // Load the food items relationship first after items created
            $foodMenu->foods = $this->foodMenuItemService->getByFoodMenuId($foodMenu->id);

            return $foodMenu;
        });

        return $foodMenu;
    }

    public function find(array $data) {
        $foodMenu      = FoodMenu::where('user_id', Auth::id())->find($data['id']);
        $foodMenuItems = $this->foodMenuItemService->getByFoodMenuId($foodMenu->id);

        $foodMenu->foods = $foodMenuItems;

        return $foodMenu;
    }

}
