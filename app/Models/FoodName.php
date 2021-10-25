<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FoodName extends Model {

    use SoftDeletes;

    protected $fillable = ['name'];

    protected $hidden = [];

    protected static function booted() {
        static::creating(function($food) {
            $food->user_id = Auth::id();
        });
    }

}
