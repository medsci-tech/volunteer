<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UnitTableSeeder::class);
        $this->call(ActivityTableSeeder::class);
        $this->call(BeanRateTableSeeder::class);

        Model::reguard();
    }
}
