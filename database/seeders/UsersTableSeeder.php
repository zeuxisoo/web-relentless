<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

    public function run() {
        DB::table('users')->insert([
            'name'     => 'test',
            'username' => 'test',
            'email'    => 'test@test.com',
            'password' => Hash::make('testtest'),
        ]);
    }

}
