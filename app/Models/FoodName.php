<?php
namespace App\Models;

use App\Api\Version1\Traits\GetOrInsertFoodFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FoodName extends Model {

    use SoftDeletes, HasFactory;
    use GetOrInsertFoodFields;

    protected $fillable = ['user_id', 'name'];

    protected $hidden = [];

    protected static function booted() {
        static::creating(function($foodName) {
            $authId = Auth::id();

            if (!property_exists($foodName, "user_id") && !empty($authId)) {
                $foodName->user_id = $authId;
            }
        });
    }

}
