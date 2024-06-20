<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('primary_categories')->insert([
            [
                'name' => 'アウター',
                'sort_order' => 1,
            ],
            [
                'name' => 'インナー',
                'sort_order' => 2,
            ],
            [
                'name' => 'スラックス',
                'sort_order' => 3,
            ],
        ]);
        DB::table('secondary_categories')->insert([
            [
                'name' => 'ダウンジャケット',
                'sort_order' => 1,
                'primary_category_id' => 1,
            ],
            [
                'name' => 'Tシャツ',
                'sort_order' => 2,
                'primary_category_id' => 2,
            ],
            [
                'name' => 'デニム',
                'sort_order' => 3,
                'primary_category_id' => 3,
            ],
            [
                'name' => 'MA-1',
                'sort_order' => 4,
                'primary_category_id' => 1,
            ],
            [
                'name' => 'タンクトップ',
                'sort_order' => 5,
                'primary_category_id' => 1,
            ],
            [
                'name' => 'ジャージ',
                'sort_order' => 6,
                'primary_category_id' => 3,
            ],
        ]);
    }
}
