<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sber_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('refresh_token');
            $table->string('access_token');
            $table->string('token_type');
            $table->smallInteger('expires_in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sber_tokens');
    }
};
