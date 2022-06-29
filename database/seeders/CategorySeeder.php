<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
            'name' => 'Television',
            'slug' => 'television',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Headphones',
            'slug' => 'headphones',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'name' => 'Mobilephone',
            'slug' => 'mobilephone',
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]
    );
    }
}
