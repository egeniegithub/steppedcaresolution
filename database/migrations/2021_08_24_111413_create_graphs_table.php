<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGraphsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graphs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('start_period_id');
            //$table->unsignedBigInteger('end_period_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('stream_id');
            $table->unsignedBigInteger('field_id');
            $table->integer('is_cumulative');
            $table->integer('is_visible');

            $table->foreign('start_period_id')->on('periods')->references('id');
            //$table->foreign('end_period_id')->on('periods')->references('id');
            $table->foreign('project_id')->on('projects')->references('id');
            $table->foreign('form_id')->on('forms')->references('id');
            $table->foreign('stream_id')->on('streams')->references('id');
            $table->foreign('field_id')->on('stream_fields')->references('id');
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
        Schema::dropIfExists('graphs');
    }
}
