<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void {
        parent::setUp();

        DB::beginTransaction();

        Artisan::call("migrate:refresh");

        // Disable auto seed the user because the user will be manually create by the api class
        // Artisan::call('db:seed', ['--class' => 'UsersTableSeeder']);
    }

    protected function tearDown(): void {
        DB::rollBack();

        parent::tearDown();
    }
}
