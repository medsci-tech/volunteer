<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->increments('id');

            $table->string('province_id', 31)->comment('省ID');
            $table->string('province', 31)->comment('省');

            $table->string('city_id', 31)->comment('市ID');
            $table->string('city', 31)->comment('市');

            $table->string('country_id', 31)->comment('区ID');
            $table->string('country', 31)->comment('区');

            $table->string('hospital_level', 31)->comment('医院等级');
            $table->string('hospital', 31)->nullable()->comment('医院');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hospitals');
    }
}
