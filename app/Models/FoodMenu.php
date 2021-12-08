<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\HasTags;

class FoodMenu extends Model {

    use SoftDeletes, HasFactory, HasTags;

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

}
