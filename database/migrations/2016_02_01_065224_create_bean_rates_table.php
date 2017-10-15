<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeanRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bean_rates', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('activity_id')->unsigned()->comment('关联项目');
            $table->foreign('activity_id')->references('id')->on('activities');

            $table->string('activity_name', 31)->comment('项目名称');
            $table->string('action_en', 31)->comment('操作en');
            $table->string('action_ch', 31)->comment('操作ch');
            $table->integer('rate')->unsigned()->comment('操作兑换积分');

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
        Schema::table('bean_rates', function(Blueprint $table) {
            $table->dropForeign('bean_rates_activity_id_foreign');
        });
        Schema::drop('bean_rates');
    }
}
