<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectsToKzktClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kzkt_classes', function (Blueprint $table) {
            $table->tinyInteger('project_id')->default(1)->comment('项目id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kzkt_classes', function (Blueprint $table) {
            //
        });
    }
}
