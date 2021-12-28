<?php
namespace App\Models;

use App\Api\Version1\Services\FoodMenuItemService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class FoodMenu extends Model {

    use SoftDeletes, HasFactory, HasTags, Searchable;

    protected $fillable = ['user_id', 'start_at', 'remark'];

    protected $hidden = [];

    protected static function booted() {
        static::creating(function($foodMenu) {
            $authId = Auth::id();

            if (!property_exists($foodMenu, "user_id") && !empty($authId)) {
                $foodMenu->user_id = $authId;
            }
        });
    }

    public function foods() {
        return $this->hasMany(FoodMenuItem::class);
    }

    // Scout
    public function searchableAs() {
        return 'food_menus_index';
    }

    public function toSearchableArray() {
        $foodMenu = $this->toArray();

        $foodMenu['foods'] = (new FoodMenuItemService())
            ->getByFoodMenuId($this->id)
            ->toArray();

        return $foodMenu;
    }

}
