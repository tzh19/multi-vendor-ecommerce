<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Vendor;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
    'name',
    'description',
    'category_id',
    'vendor_id',
    'stock',
    'price',
    'image',
    'is_active',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedStockAttribute()
    {
        return number_format($this->stock);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
