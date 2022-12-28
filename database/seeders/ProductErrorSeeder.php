<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProductErrorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_errors')->insert([
            'name' => 'Lỗi màn hình',
        ]);
        DB::table('product_errors')->insert([
            'name' => 'Lỗi bàn phím',
        ]);
        DB::table('product_errors')->insert([
            'name' => 'Lỗi launchpad',
        ]);
        DB::table('product_errors')->insert([
            'name' => 'Lỗi loa',
        ]);
        DB::table('product_errors')->insert([
            'name' => 'Lỗi cổng kết nối',
        ]); 
        DB::table('product_errors')->insert([
            'name' => 'Lỗi sạc',
        ]); 
        DB::table('product_errors')->insert([
            'name' => 'Lỗi pin',
        ]); 
        DB::table('product_errors')->insert([
            'name' => 'Lỗi phần mềm',
        ]);
        DB::table('product_errors')->insert([
            'name' => 'Các lỗi khác',
        ]);
    }
}
