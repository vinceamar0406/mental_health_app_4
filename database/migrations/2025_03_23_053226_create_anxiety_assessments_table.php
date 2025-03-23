<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anxiety_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('responses'); // Store Likert scale answers as JSON
            $table->string('impact');
            $table->integer('total_score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anxiety_assessments');
    }
};
