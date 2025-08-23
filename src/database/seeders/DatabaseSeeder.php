<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SeasonsTableSeeder::class,       #先に季節マスタ
            ProductsTableSeeder::class,      #次に商品
            ProductSeasonTableSeeder::class, #最後に中間テーブルで関連づけ
        ]);
    }
}
