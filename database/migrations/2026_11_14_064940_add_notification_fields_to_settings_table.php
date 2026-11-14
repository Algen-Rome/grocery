<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('low_stock_alerts')->default(true);
            $table->boolean('new_order_alerts')->default(true);
            $table->boolean('payment_received_alerts')->default(true);
            $table->boolean('refund_request_alerts')->default(true);
            $table->boolean('daily_sales_summary')->default(false);
            $table->boolean('weekly_report_email')->default(true);
            $table->boolean('in_app_notifications')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'low_stock_alerts',
                'new_order_alerts',
                'payment_received_alerts',
                'refund_request_alerts',
                'daily_sales_summary',
                'weekly_report_email',
                'in_app_notifications',
            ]);
        });
    }
};
