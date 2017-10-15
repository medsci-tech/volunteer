<?php

use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('units')->insert(
            ['short_name' => 'novo_nordisk',  'full_name' => '诺和诺德' ]
        );

        DB::table('units')->insert(
            ['short_name' => 'medscience_tech', 'full_name' => '迈德科技' ]
        );
    }
}
