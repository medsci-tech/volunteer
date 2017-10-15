<?php

use Illuminate\Database\Seeder;
use App\Model\Activity;

class BeanRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kzktId = Activity::where('title', '空中课堂')->first()->id;
        $hpxtId = Activity::where('title', '黄埔学堂')->first()->id;
        $qykjId = Activity::where('title', '千院科教')->first()->id;
        $emyId  = Activity::where('title', 'e名医')->first()->id;

        DB::table('bean_rates')->insert([
            'activity_id' => $kzktId,
            'activity_name' => '空中课堂',
            'action_en' => 'kzkt_apply_doctor',
            'action_ch' => '申请报名',
            'rate' => 100
        ]);

        DB::table('bean_rates')->insert([
            'activity_id' => $hpxtId,
            'activity_name' => '黄埔学堂',
            'action_en' => 'hpxt_apply_class',
            'action_ch' => '申请班级',
            'rate' => 100
        ]);

        DB::table('bean_rates')->insert([
            'activity_id' => $qykjId,
            'activity_name' => '千院科教',
            'action_en' => 'qykj_invite_doctor',
            'action_ch' => '邀请医师',
            'rate' => 100
        ]);

    }
}
