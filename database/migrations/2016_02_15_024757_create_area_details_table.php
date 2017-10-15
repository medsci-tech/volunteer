<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('area_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('province', 31)->nullable()->comment('省份');
            $table->string('dbm_name', 63)->nullable()->comment('DBM名称');
            $table->string('dbm_code', 63)->nullable()->comment('DBM编码');
            $table->string('represent_name', 63)->nullable()->comment('代表名称');
            $table->string('represent_code', 63)->nullable()->comment('代表编码');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('area_details');
    }
}
