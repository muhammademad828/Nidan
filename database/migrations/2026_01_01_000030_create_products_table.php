<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('slug')->unique();
            $table->string('short_description_en', 500)->nullable();
            $table->string('short_description_ar', 500)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->string('sku', 100)->unique()->comment('Mandatory unique product code — manual entry');
            $table->string('primary_image_path', 500)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_giftable')->default(true);
            $table->boolean('requires_delivery_slot')->default(false);
            $table->integer('weight_grams')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_ar')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_featured');
            $table->index('is_active');
            $table->index(['category_id', 'region_id', 'is_active', 'display_order'], 'products_catalog_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
