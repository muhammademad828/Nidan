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
        Schema::table('add_ons', function (Blueprint $table) {
            $table->boolean('has_message')->default(false)->after('image');
            $table->string('placeholder_ar')->nullable()->after('has_message');
            $table->string('placeholder_en')->nullable()->after('placeholder_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_ons', function (Blueprint $table) {
            $table->dropColumn(['has_message', 'placeholder_ar', 'placeholder_en']);
        });
    }
};
