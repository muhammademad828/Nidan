<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 60)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('cart_data');
            $table->timestamps();
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_sessions');
    }
};