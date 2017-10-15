<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateClassDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 31)->comment('课程名');
            $table->string('unit', 31)->comment('课程单元');
            $table->string('lecturer')->comment('授课人');
            $table->string('hospital')->comment('医院');
            $table->string('type')->comment('课程类别');
            $table->date('published_at')->comment('授课时间');
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
        Schema::drop('class_details');
    }
}
