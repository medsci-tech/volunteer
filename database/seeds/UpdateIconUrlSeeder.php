<?php

use Illuminate\Database\Seeder;

class UpdateIconUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'thousand.png',
            'huangpu.png',
            'medassistant.png',
            'aircourse.png',
            'edoctor.png',
            'private.png',
            'merck.png'
        ];


        for ($i = 0; $i < 7; $i++) {
            DB::table('activitys')->where('id', $i + 1)->update([
                'icon_url' => '/image/icons/' . $array[$i]
            ]);
        }
    }
}
