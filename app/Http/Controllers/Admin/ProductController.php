<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Product\ProductIndexRequest;
use App\Services\QueryProcessor;
use App\Traits\JSendResponse;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
