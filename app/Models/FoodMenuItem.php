<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodMenuItem extends Model {

    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'food_menu_id', 'food_name_id', 'food_unit_id', 'quantity'];

    protected $hidden = [];

    public function name() {
        return $this->belongsTo(FoodName::class, "food_name_id", "id");
    }

    public function unit() {
        return $this->belongsTo(FoodUnit::class, "food_unit_id", "id");
    }

}
