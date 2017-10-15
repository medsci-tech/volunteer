<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 31)->comment('项目标题');
            $table->text('abstract')->comment('项目摘要');
            $table->string('icon_url')->nullable()->comment('项目图标url');
            $table->string('index_url')->nullable()->comment('项目链接');

            $table->integer('unit_id')->unsigned()->comment('主办单位');
            $table->foreign('unit_id')->references('id')->on('units');

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
        Schema::table('activities', function(Blueprint $table) {
            $table->dropForeign('activities_unit_id_foreign');
        });
        Schema::drop('activities');
    }
}
