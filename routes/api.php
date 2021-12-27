<?php

use App\Api\Version1\Controllers\AuthController;
use App\Api\Version1\Controllers\FoodMenuController;
use App\Api\Version1\Controllers\FoodNameController;
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
        // api.food.*.*
        Route::prefix("food")->group(function() {
            // api.food.name.*
            Route::prefix("name")->group(function() {
                Route::post("store",    [FoodNameController::class, 'store'])->name('api.food.name.store');
                Route::get("list",      [FoodNameController::class, 'list'])->name('api.food.name.list');
                Route::get("show/{id}", [FoodNameController::class, 'show'])->name('api.food.name.show');
                Route::post("update",   [FoodNameController::class, 'update'])->name('api.food.name.update');
                Route::get("search",    [FoodNameController::class, 'search'])->name('api.food.name.search');
            });

            // api.food.unit.*
            Route::prefix("unit")->group(function() {
                Route::post("store",    [FoodUnitController::class, 'store'])->name('api.food.unit.store');
                Route::get("list",      [FoodUnitController::class, 'list'])->name('api.food.unit.list');
                Route::get("show/{id}", [FoodUnitController::class, 'show'])->name('api.food.unit.show');
                Route::post("update",   [FoodUnitController::class, 'update'])->name('api.food.unit.update');
                Route::get("search",    [FoodUnitController::class, 'search'])->name('api.food.unit.search');
            });

            // api.food.menu.*
            Route::prefix("menu")->group(function() {
                Route::post("store",    [FoodMenuController::class, 'store'])->name('api.food.menu.store');
                Route::get("list",      [FoodMenuController::class, 'list'])->name('api.food.menu.list');
                Route::get("show/{id}", [FoodMenuController::class, 'show'])->name('api.food.menu.show');
                Route::post("update",   [FoodMenuController::class, 'update'])->name('api.food.menu.update');
                Route::get("search",    [FoodMenuController::class, 'search'])->name('api.food.menu.search');
            });
        });
    });

});
