<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Food extends Model {

    use SoftDeletes;

    protected $table = "foods"; // set the table name is plural not singular

    protected $fillable = ['name'];

    protected $hidden = [];

    protected static function booted() {
        static::creating(function($food) {
            $food->user_id = Auth::id();
        });
    }

}
