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
        Schema::create('data_source_management', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('source_type')->nullable();
            $table->string('source_name');
            $table->string('api_endpoint')->nullable();
            $table->string('auth_key')->nullable();
            $table->text('value')->nullable();
            $table->enum('status', ['pending', 'connected', 'disconnected', 'not_configured'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_source_management');
    }
};
