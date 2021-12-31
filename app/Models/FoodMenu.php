<?php
namespace App\Models;

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

        if (array_key_exists('tags', $foodMenu)) {
            $foodMenu['tags'] = collect($foodMenu['tags'])
                ->pluck('name.'.env('TAG_DEFAULT_LOCALE', 'en'))
                ->implode(',');
        }

        if (array_key_exists('foods', $foodMenu)) {
            $foods = [];

            foreach($foodMenu['foods'] as $food) {
                $foodName     = $food['name']['name'];
                $foodUnit     = $food['unit']['name'];
                $foodQuantity = $food['quantity'];

                $foods[] = "{$foodName} {$foodQuantity} {$foodUnit}";
            }

            $foodMenu['foods'] = implode("|", $foods);
        }

        return $foodMenu;
    }

    protected function makeAllSearchableUsing($query) {
        return $query->with('foods.name', 'foods.unit', 'tags');
    }

}
