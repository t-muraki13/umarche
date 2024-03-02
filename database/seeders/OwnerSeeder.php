<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->insert([
            ['name' => 'Duffy',
            'email' => 'duffy@test.com',
            'password' => Hash::make('password123'),
            'created_at' => '2024/01/13 11:11:11'
            ],
            ['name' => 'Deca',
            'email' => 'deca@test.com',
            'password' => Hash::make('password123'),
            'created_at' => '2024/01/15 11:11:11'
            ],
            ['name' => 'ina',
            'email' => 'ina@test.com',
            'password' => Hash::make('password123'),
            'created_at' => '2024/01/22 11:11:11'
            ]
        ]);
    }
}
