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
        Schema::table('appointments', function (Blueprint $table) {
            // Add missing columns
            $table->unsignedBigInteger('user_id')->after('id'); // Define user_id
            $table->unsignedBigInteger('assessment_id')->after('user_id'); // Define assessment_id

            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');

            // Add status and appointment_date columns
            $table->enum('status', ['none', 'pending', 'approved', 'completed'])->default('pending')->after('assessment_id');
            $table->dateTime('appointment_date')->nullable()->after('status'); // Nullable datetime column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['user_id']);
            $table->dropForeign(['assessment_id']);

            // Drop the columns we added
            $table->dropColumn(['user_id', 'assessment_id', 'status', 'appointment_date']);
        });
    }
};
