<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineOrder extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'contact_number',
        'delivery_address',
        'order_type',
        'payment_method',
        'subtotal',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OnlineOrderItem::class);
    }
}
