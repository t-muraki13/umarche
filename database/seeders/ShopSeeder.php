<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shops')->insert([
            [
                'owner_id' => 1,
                'name' => 'ダッフィーの家',
                'infomation' => 'ダッフィーの好きなものが揃います。',
                'filename' => 'sample1.jpg',
                'is_selling' => true
            ],
            [
                'owner_id' => 2,
                'name' => 'デカちゃんの家',
                'infomation' => 'デカちゃんの好きなものが揃います。',
                'filename' => 'sample5.jpg',
                'is_selling' => true
            ]
        ]);
    }
}
