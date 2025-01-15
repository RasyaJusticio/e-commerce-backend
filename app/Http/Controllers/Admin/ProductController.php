<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Product\ProductIndexRequest;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;
use App\Services\QueryProcessor;
use App\Traits\JSendResponse;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use JSendResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(ProductIndexRequest $request)
    {
        $validatedData = $request->validated();
        $queryProcessor = app(QueryProcessor::class);

        $page = $validatedData['page'] ?? 1;
        $per_page = $validatedData['per_page'] ?? 10;
        $sort_by = $validatedData['sort_by'] ?? 'id';
        $sort_order = $validatedData['sort_order'] ?? 'asc';
        $filter = $validatedData['filter'] ?? [];

        $query = Product::query();

        $queryProcessor->filter($query, $filter);
        $queryProcessor->sort($query, $sort_by, $sort_order);
        $queryProcessor->paginate($query, $page, $per_page);

        $products = $query->get();

        return $this->jsend_success([
            'products' => $products,
            'meta' => [
                'page' => $page,
                'per_page' => $per_page,
                'total_page' => ceil(Product::count() / $per_page)
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $validatedData = $request->validated();

        $product = Product::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'] ?? Str::slug($validatedData['name']),
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'] ?? 0,
        ]);

        return $this->jsend_success([
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->jsend_success([
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        $product->update($validatedData);

        return $this->jsend_success([
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
