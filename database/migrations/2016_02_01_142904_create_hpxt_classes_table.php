<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHpxtClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hpxt_classes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('volunteer_id')->unsigned()->comment('志愿者');
            $table->foreign('volunteer_id')->references('id')->on('volunteers');

            $table->string('scale', 11)->comment('规模');
            $table->string('mode', 11)->comment('形式');
            $table->string('name', 11)->comment('班级名称');
            $table->date('time')->comment('班级招募时间');
            $table->boolean('state')->default(false)->comment('班级状态-审核中/审核通过');

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
        Schema::table('hpxt_classes', function(Blueprint $table) {
            $table->dropForeign('hpxt_classes_volunteer_id_foreign');
        });
        Schema::drop('hpxt_classes');
    }
}
