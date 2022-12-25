<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'name' => 'factory',
        ]);
        DB::table('statuses')->insert([
            'name' => 'agent',
        ]);
        DB::table('statuses')->insert([
            'name' => 'customer',
        ]);
        DB::table('statuses')->insert([
            'name' => 'agent_fail',
        ]);
        DB::table('statuses')->insert([
            'name' => 'warranty',
        ]);
        DB::table('statuses')->insert([
            'name' => 'factory_fail',
        ]);
    }
}
