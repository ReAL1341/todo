<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelTableSeeder extends Seeder
{
    public function run()
    {
        $param = [
            'name' => 'やることリスト',
        ];

        DB::table('channel')->insert($param);
    }
}
