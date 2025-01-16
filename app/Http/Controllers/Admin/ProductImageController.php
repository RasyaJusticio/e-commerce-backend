<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Traits\JSendResponse;
use App\Http\Requests\Admin\Product\ProductImageStoreRequest;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    use JSendResponse;

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductImageStoreRequest $request, Product $product)
    {
        $image = $request->file('image');

        $path = Storage::disk('public')->putFileAs('products', $image, $image->hashName());
        $product->images()->create([
            'path' => $path,
        ]);

        return $this->jsend_success(null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductImage $productImage)
    {
        $productImage->delete();

        return $this->jsend_success(null);
    }
}
