<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stream_id');
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id');
            $table->string('isRequired')->default(1)->comment('1 = no, 2 = yes');
            $table->string('fieldName');
            $table->string('fieldType');
            $table->string('isDuplicate')->nullable()->comment('1 = no, 2 = yes');
            $table->string('isCumulative')->nullable()->comment('1 = no, 2 = yes');
            $table->string('fieldOptions')->nullable();
            $table->text('tableData')->nullable();
            $table->integer('orderCount');
            $table->timestamps();

            $table->foreign('stream_id')->references('id')->on('streams');
            $table->foreign('form_id')->references('id')->on('forms');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stream_fields');
    }
}
