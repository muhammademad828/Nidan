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
            $table->string('quote_en')->nullable();
            $table->string('quote_ar')->nullable();
            $table->string('quote_author_en')->nullable();
            $table->string('quote_author_ar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['quote_en', 'quote_ar', 'quote_author_en', 'quote_author_ar']);
        });
    }
};
