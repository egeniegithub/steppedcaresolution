<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('user_id');

            $table->integer('forum_participants')->nullable();
            $table->integer('unique_visitors')->nullable();
            $table->integer('two_or_more_users')->nullable();
            $table->integer('three_or_more_users')->nullable();
            $table->integer('downloaded_resources')->nullable();
            $table->integer('self_help_resources')->nullable();
            $table->integer('demographic_data')->nullable();
            $table->integer('user_satisfaction')->nullable();
            $table->integer('outcomes_data')->nullable();
            $table->enum('status', ['Draft', 'In-progress', 'Published'])->nullable();
            $table->text('narrative')->nullable();
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
        Schema::dropIfExists('special_forms');
    }
}
