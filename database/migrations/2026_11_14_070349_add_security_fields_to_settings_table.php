<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('session_timeout')->default('30 minutes');
            $table->boolean('two_factor_enabled')->default(true);
            $table->boolean('login_alert_emails')->default(true);
            $table->boolean('auto_logout_idle')->default(true);
            $table->boolean('allow_multiple_devices')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'session_timeout',
                'two_factor_enabled',
                'login_alert_emails',
                'auto_logout_idle',
                'allow_multiple_devices',
            ]);
        });
    }
};
