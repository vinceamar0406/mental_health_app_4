<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('screening_responses', function (Blueprint $table) {
        $table->string('predicted_condition')->nullable();  // Add the predicted_condition column
    });
}

public function down()
{
    Schema::table('screening_responses', function (Blueprint $table) {
        $table->dropColumn('predicted_condition');  // This will allow you to roll back the migration
    });
}

};
