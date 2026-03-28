<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique()->comment('NIDAN-YYYYMMDD-XXXX');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('region_id')->constrained();
            $table->enum('status', ['pending', 'paid', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('EGP');
            $table->string('company_name')->nullable();
            $table->string('contact_person');
            $table->string('contact_phone', 20);
            $table->boolean('is_gift')->default(false);
            $table->boolean('is_surprise')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            // Orders are NEVER soft deleted — permanent archive

            $table->index('status');
            $table->index('user_id');
            $table->index('region_id');
            $table->index('company_name');
            $table->index('created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
            $table->foreignId('bundle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name')->comment('Snapshot — frozen at order time');
            $table->string('product_sku', 100)->comment('Snapshot — frozen at order time');
            $table->string('variation_name')->nullable()->comment('Snapshot');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->json('addons')->nullable()->comment('Snapshot of add-ons with prices');
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_sku');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->text('notes')->nullable();
            $table->string('changed_by', 50)->default('system');
            $table->timestamps();

            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
