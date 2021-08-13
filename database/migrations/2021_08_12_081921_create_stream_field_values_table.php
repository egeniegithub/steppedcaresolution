<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_field_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('stream_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stream_field_id');
            $table->string('value');
            $table->timestamps();

            $table->foreign('period_id')->references('id')->on('periods');
            $table->foreign('form_id')->references('id')->on('forms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stream_id')->references('id')->on('streams');
            $table->foreign('stream_field_id')->references('id')->on('stream_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stream_field_values');
    }
}
