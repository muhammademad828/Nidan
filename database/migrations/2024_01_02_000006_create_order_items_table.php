<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name_snapshot', 380);
            $table->decimal('selling_price_snapshot', 12, 2)->default(0);
            $table->decimal('cost_price_snapshot', 12, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->text('custom_notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });

        Schema::create('order_item_addons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('add_on_id');
            $table->string('add_on_name_snapshot', 190);
            $table->decimal('price_snapshot', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->foreign('add_on_id')->references('id')->on('add_ons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_addons');
        Schema::dropIfExists('order_items');
    }
};