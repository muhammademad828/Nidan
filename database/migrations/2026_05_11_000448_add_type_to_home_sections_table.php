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
        Schema::table('home_sections', function (Blueprint $table) {
            $table->string('type')->default('collection')->after('id');
            // Drop foreign key if it exists to allow changing to nullable
            // Since sqlite doesn't support dropping foreign keys easily, we'll just make it nullable.
            // Wait, this is MySQL.
            $table->unsignedBigInteger('tag_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('tag_id')->nullable(false)->change();
        });
    }
};
