<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FoodMenuItem extends Model {

    use SoftDeletes, HasFactory;

    protected $fillable = ['user_id', 'food_menu_id', 'food_name_id', 'food_unit_id'];

    protected $hidden = [];

    public function foodName() {
        return $this->belongsTo(FoodName::class);
    }

    public function foodUnit() {
        return $this->belongsTo(FoodUnit::class);
    }

}
