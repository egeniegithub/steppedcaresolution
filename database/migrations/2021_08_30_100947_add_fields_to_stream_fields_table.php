<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToStreamFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stream_fields', function (Blueprint $table) {
            $table->string('value')->nullable()->after('orderCount');
            $table->bigInteger('cumulative_value')->nullable()->after('orderCount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stream_fields', function (Blueprint $table) {
            //
        });
    }
}
