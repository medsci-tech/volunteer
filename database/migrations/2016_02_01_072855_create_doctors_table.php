<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('hospital_id')->unsigned()->comment('医院ID');
            $table->foreign('hospital_id')->references('id')->on('hospitals');

            $table->string('name', 31)->comment('名字');
            $table->string('phone', 11)->comment('手机号码');
            $table->string('phone_code', 11)->comment('手机验证码');
            $table->string('email', 31)->nullable()->comment('电子邮件');
            $table->string('qq', 31)->nullable()->comment('qq');

            $table->string('office', 11)->comment('科室');
            $table->string('title', 11)->nullable()->comment('职称');

            $table->index('phone');
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
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign('doctors_hospital_id_foreign');
        });

        Schema::drop('doctors');
    }
}
