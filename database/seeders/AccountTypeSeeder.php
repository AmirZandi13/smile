<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['normal', 'special'];

        foreach ($types as $index => $type) {
            AccountType::create([
                'title' => $type,
                'description' => $type,
                'code' => $index + 1,
            ]);
        }
    }
}
