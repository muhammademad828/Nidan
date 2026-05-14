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
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('secondary_button_text_en')->nullable();
            $table->string('secondary_button_text_ar')->nullable();
            $table->string('secondary_button_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['secondary_button_text_en', 'secondary_button_text_ar', 'secondary_button_url']);
        });
    }
};
