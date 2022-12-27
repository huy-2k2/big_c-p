<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
//
class DepotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('depots')->insert([
            'id' => 1,
            'depot_name' => 'customer_depot',
            'owner_id' => 0,
            'size' => 9999,
            'status_b' => 1,
        ]);
    }
}
