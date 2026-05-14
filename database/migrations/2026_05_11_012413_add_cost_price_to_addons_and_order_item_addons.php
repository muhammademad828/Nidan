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
            $table->decimal('cost_price', 10, 2)->default(0)->after('price');
        });

        Schema::table('order_item_addons', function (Blueprint $table) {
            $table->decimal('cost_price_snapshot', 10, 2)->default(0)->after('price_snapshot');
        });
    }

    public function down(): void
    {
        Schema::table('add_ons', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });

        Schema::table('order_item_addons', function (Blueprint $table) {
            $table->dropColumn('cost_price_snapshot');
        });
    }
};
