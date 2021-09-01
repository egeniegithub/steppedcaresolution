<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreviousIdsOnSyncPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->bigInteger('previous_id')->nullable()->after('updated_by');
        });

        Schema::table('streams', function (Blueprint $table) {
            $table->bigInteger('previous_id')->nullable()->after('form_data');
        });

        Schema::table('stream_fields', function (Blueprint $table) {
            $table->bigInteger('previous_id')->nullable()->after('cumulative_value');
        });

        Schema::table('stream_field_grids', function (Blueprint $table) {
            $table->bigInteger('previous_id')->nullable()->after('cumulative_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
