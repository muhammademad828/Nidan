<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50)->default('home')->index();
            $table->string('name');
            $table->string('component_name', 80);
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['page', 'component_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_sections');
    }
};
