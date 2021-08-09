<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_change_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stream_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('stream_id')->references('id')->on('streams');
            $table->foreign('user_id')->references('id')->on('users');
            $table->longText('old_data')->nullable();
            $table->longText('new_data')->nullable();
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
        Schema::dropIfExists('stream_change_logs');
    }
}
