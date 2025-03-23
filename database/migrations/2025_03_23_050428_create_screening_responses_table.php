<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('screening_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('responses'); // Stores the responses in JSON format
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('screening_responses');
    }
};
