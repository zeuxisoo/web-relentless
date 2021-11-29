<?php

namespace App\Providers;

use App\Models\UserAccessToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // N+1
        Model::preventLazyLoading(app()->isProduction() === false);

        // Custom polymorphic types like morphTo
        Relation::morphMap([
            'food_menus' => 'App\Models\FoodMenu',
        ]);

        // Custom Sanctum access token model
        Sanctum::usePersonalAccessTokenModel(UserAccessToken::class);

        // Log sql query
        if(env('ENABLE_SQL_QUERY_LOG') === true) {
            DB::listen(function($query) {
                $format    = "[%s] %s <= %s\n";
                $datetime  = Carbon::now()->format("Y-m-d H:m:s");
                $arguments = '['.implode(', ', $query->bindings).']';

                $log = sprintf($format, $datetime, $query->sql, $arguments);

                File::append(storage_path('/logs/query.log'), $log);
            });
        }
    }
}
