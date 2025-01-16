<?php

namespace App\Models;

use App\Events\ProductImageDeleting;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'path'
    ];

    protected $dispatchesEvents = [
        'deleting' => ProductImageDeleting::class,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
