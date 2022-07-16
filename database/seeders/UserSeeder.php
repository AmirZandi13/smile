<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUserName = env('TEST_USER_NAME', 'smile');
        $testUserEmail = env('TEST_USER_EMAIL', 'admin@smile.com');
        $testUserPassword = env('TEST_USER_PASSWORD', '123456');

         User::factory()->create([
             'name' => $testUserName,
             'email' => $testUserEmail,
             'password' => Hash::make($testUserPassword),
         ]);
    }
}
