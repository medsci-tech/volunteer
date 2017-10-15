<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepresentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('represent_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dbm_code', 63)->nullable()->comment('DBM编码');
            $table->string('represent_name', 63)->nullable()->comment('代表名称');
            $table->string('represent_code', 63)->nullable()->comment('代表编码');
            $table->string('phone', 11)->nullable()->comment('电话');

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
        Schema::drop('represent_details');
    }
}
