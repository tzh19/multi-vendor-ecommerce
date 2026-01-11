<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
       'user_id',
        'order_number',
        'subtotal',
        'shipping_fee',
        'total_price',
        'payment_method',
        'address',
        'status',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function belongsToVendor(int $vendorId): bool
    {
        return $this->items()
            ->whereHas('product', fn ($q) => $q->where('vendor_id', $vendorId))
            ->exists();
    }
}
