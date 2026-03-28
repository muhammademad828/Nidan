<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('time_from');
            $table->time('time_to');
            $table->integer('capacity');
            $table->integer('booked_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['date', 'region_id', 'is_active'], 'slots_lookup_idx');
            $table->index(['date', 'booked_count']);
        });

        Schema::create('blackout_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reason');
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index('date');
        });

        Schema::create('gift_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('sender_name');
            $table->string('sender_phone', 20);
            $table->string('sender_email')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone', 20);
            $table->text('recipient_address');
            $table->string('recipient_city', 100);
            $table->text('gift_message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('gift_wrap_type', 50)->nullable();
            $table->string('gift_card_design', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_slot_id')->nullable()->constrained()->nullOnDelete();
            $table->date('preferred_date');
            $table->time('preferred_time_from');
            $table->time('preferred_time_to');
            $table->text('special_instructions')->nullable();
            $table->string('delivery_area', 100);
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_details');
        Schema::dropIfExists('gift_details');
        Schema::dropIfExists('blackout_dates');
        Schema::dropIfExists('delivery_slots');
    }
};
