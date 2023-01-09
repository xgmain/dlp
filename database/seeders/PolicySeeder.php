<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Policy::create([
            'name' => 'Australia Standard',
            'client_id' => 1,
        ]);
    }
}
