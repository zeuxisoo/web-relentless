<?php

use App\Api\Version1\Controllers\AuthController;
use App\Api\Version1\Controllers\FoodController;
use App\Api\Version1\Controllers\FoodUnitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("v1")->group(function() {

    // api.auth.*
    Route::prefix("auth")->group(function() {
        Route::post("login", [AuthController::class, 'login'])->name('api.auth.login');

        Route::middleware(["auth:sanctum"])->group(function() {
            Route::get("logout", [AuthController::class, 'logout'])->name('api.auth.logout');
        });
    });

    Route::middleware(["auth:sanctum"])->group(function() {
        // api.food.*
        Route::prefix("food")->group(function() {
            Route::post("store", [FoodController::class, 'store'])->name('api.food.store');

            // api.food.unit.*
            Route::prefix("unit")->group(function() {
                Route::post("store", [FoodUnitController::class, 'store'])->name('api.food.unit.store');
            });
        });
    });

});
