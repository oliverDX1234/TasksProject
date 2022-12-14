<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => "User1",
                'email' => "a@abc.org",
                'password' => Hash::make('Secret!'),
                "role" => "admin",
                "email_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'name' => "User2",
                'email' => "b@abc.org",
                'password' => Hash::make('Secret!'),
                "role" => "user",
                "email_verified_at" => now(),
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);
    }
}
