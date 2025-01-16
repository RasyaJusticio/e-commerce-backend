<?php

namespace App\Listeners;

use App\Events\ProductImageDeleting;
use Illuminate\Support\Facades\Storage;

class DeleteProductImages
{
    public function handle(ProductImageDeleting $event): void
    {
        if (Storage::disk('public')->exists($event->productImage->path)) {
            Storage::disk('public')->delete($event->productImage->path);
        }
    }
}
