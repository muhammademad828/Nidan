<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->enum('type', ['text', 'textarea', 'image', 'boolean', 'json'])->default('text');
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index('group');
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50);
            $table->string('section_key', 100);
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('subtitle_en', 500)->nullable();
            $table->string('subtitle_ar', 500)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('button_text_en', 100)->nullable();
            $table->string('button_text_ar', 100)->nullable();
            $table->string('button_url', 500)->nullable();
            $table->string('background_image', 500)->nullable();
            $table->json('extra_data')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['page', 'section_key']);
            $table->index('display_order');
        });

        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50);
            $table->string('key', 150)->unique();
            $table->text('value_en')->nullable();
            $table->text('value_ar')->nullable();
            $table->enum('type', ['text', 'textarea', 'html', 'json', 'image'])->default('text');
            $table->boolean('is_rich_text')->default(false);
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index('page');
        });

        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50)->unique();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_ar')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->string('og_title_en')->nullable();
            $table->string('og_title_ar')->nullable();
            $table->text('og_description_en')->nullable();
            $table->text('og_description_ar')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
        Schema::dropIfExists('content_blocks');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('site_settings');
    }
};
