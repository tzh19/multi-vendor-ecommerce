<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'store_name',
        'store_description',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
