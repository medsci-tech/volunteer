<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKzktClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kzkt_classes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('volunteer_id')->unsigned()->comment('志愿者');
            $table->foreign('volunteer_id')->references('id')->on('volunteers');

            $table->integer('doctor_id')->unsigned()->comment('医师');
            $table->foreign('doctor_id')->references('id')->on('doctors');

            $table->string('type', 11)->comment('课程班');
            $table->string('login_number', 11)->comment('登录密码');
            $table->string('invite_number', 11)->comment('邀请码');
            $table->integer('style')->default(1)->comment('学习方式');
            $table->boolean('status')->default(false)->comment('状态');

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
        Schema::table('kzkt_classes', function(Blueprint $table) {
            $table->dropForeign('kzkt_classes_volunteer_id_foreign');
            $table->dropForeign('kzkt_classes_doctor_id_foreign');
        });
        Schema::drop('kzkt_classes');
    }
}
