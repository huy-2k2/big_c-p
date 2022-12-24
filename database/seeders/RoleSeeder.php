<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
        ]);
        DB::table('roles')->insert([
            'name' => 'factory',
        ]);
        DB::table('roles')->insert([
            'name' => 'warranty',
        ]);
        DB::table('roles')->insert([
            'name' => 'agent',
        ]);
        DB::table('roles')->insert([
            'name' => 'customer',
        ]); 
    }
}
