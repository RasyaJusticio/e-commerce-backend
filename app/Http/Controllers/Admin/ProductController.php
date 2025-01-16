<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Traits\JSendResponse;
use App\Services\QueryProcessor;
use App\Http\Requests\Admin\Product\ProductAttachCategoryRequest;
use App\Http\Requests\Admin\Product\ProductDetachCategoryRequest;
use App\Http\Requests\Admin\Product\ProductIndexRequest;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    use JSendResponse;

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

        $products = $query->with(['categories'])->get();

        return $this->jsend_success([
            'products' => $products,
            'meta' => [
                'page' => $page,
                'per_page' => $per_page,
                'total_page' => ceil(Product::count() / $per_page)
            ]
        ]);
    }

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
        $product->categories()->attach($validatedData['categories']);

        $product->load(['categories']);

        return $this->jsend_success([
            'product' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load(['categories']);

        return $this->jsend_success([
            'product' => $product
        ]);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        $product->update($validatedData);
        $product->load(['categories']);

        return $this->jsend_success([
            'product' => $product
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->jsend_success(null);
    }

    public function attachCategories(ProductAttachCategoryRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        $product->categories()->syncWithoutDetaching($validatedData['categories']);

        $product->load(['categories']);

        return $this->jsend_success([
            'product' => $product
        ]);
    }

    public function detachCategories(ProductDetachCategoryRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        $product->categories()->detach($validatedData['categories']);

        $product->load(['categories']);

        return $this->jsend_success([
            'product' => $product
        ]);
    }
}
