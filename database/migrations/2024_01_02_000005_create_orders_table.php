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
            $table->string('order_number', 30)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_name', 190)->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->string('guest_email')->nullable();
            $table->enum('guest_gender', ['male', 'female', 'other'])->nullable();
            $table->string('city', 100);
            $table->text('address');
            $table->string('phone', 20);
            $table->enum('status', [
                'pending_confirmation',
                'confirmed',
                'in_preparation',
                'ready_for_shipping',
                'out_for_delivery',
                'delivered',
                'cancelled',
                'refunded',
            ])->default('pending_confirmation');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('net_profit', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};