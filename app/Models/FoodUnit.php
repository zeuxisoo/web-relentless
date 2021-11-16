<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FoodUnit extends Model {

    use SoftDeletes, HasFactory;

    protected $fillable = ['name'];

    protected $hidden = [];

    protected static function booted() {
        static::creating(function($foodUnit) {
            $authId = Auth::id();

            if (!property_exists($foodUnit, "user_id") && !empty($authId)) {
                $foodUnit->user_id = $authId;
            }
        });
    }

}
