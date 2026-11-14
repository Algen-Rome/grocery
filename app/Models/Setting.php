<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'store_name',
        'contact_number',
        'store_address',
        'currency',
        'timezone',
        'email_notifications',
        'sms_notifications',
        'in_app_notifications',
        'cod_enabled',
        'gcash_enabled',
        'card_enabled',
        'maya_enabled',
        'payment_gateway',
        'vat_rate',
        'gcash_number',
        'receipt_footer',
        'low_stock_alerts',
        'new_order_alerts',
        'payment_received_alerts',
        'refund_request_alerts',
        'daily_sales_summary',
        'weekly_report_email',
        'session_timeout',
        'two_factor_enabled',
        'login_alert_emails',
        'auto_logout_idle',
        'allow_multiple_devices',
    ];
}
