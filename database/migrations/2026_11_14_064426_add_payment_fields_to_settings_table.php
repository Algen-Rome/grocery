<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('cod_enabled')->default(true);
            $table->boolean('gcash_enabled')->default(true);
            $table->boolean('card_enabled')->default(true);
            $table->boolean('maya_enabled')->default(false);
            $table->string('payment_gateway')->default('PayMongo');
            $table->string('vat_rate')->default('12%');
            $table->string('gcash_number')->nullable();
            $table->string('receipt_footer')->default('Thank you for shopping with us!');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'cod_enabled',
                'gcash_enabled',
                'card_enabled',
                'maya_enabled',
                'payment_gateway',
                'vat_rate',
                'gcash_number',
                'receipt_footer',
            ]);
        });
    }
};
