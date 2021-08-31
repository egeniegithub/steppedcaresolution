<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamFieldGridsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_field_grids', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('is_dropdown')->comment('0 = no, 1 = yes');
            $table->string('field_options')->nullable();
            $table->integer('order_count')->nullable();
            $table->unsignedBigInteger('stream_field_id');
            $table->string('value')->nullable();
            $table->bigInteger('cumulative_value')->nullable();
            $table->foreign('stream_field_id')->references('id')->on('stream_fields');
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
        Schema::dropIfExists('stream_field_grids');
    }
}
