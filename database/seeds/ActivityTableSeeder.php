<?php

use Illuminate\Database\Seeder;
use App\Model\Unit;

class ActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $novoId = Unit::where('short_name', 'novo_nordisk')->first()->id;

        DB::table('activities')->insert([
                'title' => '空中课堂',
                'abstract' => '摘要',
                'unit_id' => $novoId
        ]);

        DB::table('activities')->insert([
            'title' => '黄埔学堂',
            'abstract' => '摘要',
            'unit_id' => $novoId
        ]);

        DB::table('activities')->insert([
            'title' => '医师助手',
            'abstract' => '摘要',
            'unit_id' => $novoId
        ]);

        DB::table('activities')->insert([
            'title' => '甲状腺病例讨论',
            'abstract' => '摘要',
            'unit_id' => $novoId
        ]);

        DB::table('activities')->insert([
            'title' => 'E名医下基层',
            'abstract' => '摘要',
            'unit_id' => $novoId
        ]);

        DB::table('activities')->insert([
            'title' => '甲状腺公开课',
            'abstract' => '摘要',
            'unit_id' => $novoId
        ]);

    }

} /*class*/
