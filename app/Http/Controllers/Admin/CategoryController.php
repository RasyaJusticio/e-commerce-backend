<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\JSendResponse;
use App\Services\QueryProcessor;
use App\Http\Requests\Admin\Category\CategoryIndexRequest;
use App\Http\Requests\Admin\Category\CategoryStoreRequest;
use App\Http\Requests\Admin\Category\CategoryUpdateRequest;

class CategoryController extends Controller
{
    use JSendResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(CategoryIndexRequest $request)
    {
        $validatedData = $request->validated();
        $queryProcessor = app(QueryProcessor::class);

        $page = $validatedData['page'] ?? 1;
        $per_page = $validatedData['per_page'] ?? 10;
        $sort_by = $validatedData['sort_by'] ?? 'id';
        $sort_order = $validatedData['sort_order'] ?? 'asc';
        $filter = $validatedData['filter'] ?? [];

        $query = Category::query();

        $queryProcessor->filter($query, $filter);
        $queryProcessor->sort($query, $sort_by, $sort_order);
        $queryProcessor->paginate($query, $page, $per_page);

        $categories = $query->get();

        return $this->jsend_success([
            'categories' => $categories,
            'meta' => [
                'page' => $page,
                'per_page' => $per_page,
                'total_page' => ceil(Category::count() / $per_page)
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::create($validatedData);

        return $this->jsend_success([
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->jsend_success([
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $validatedData = $request->validated();

        $category->update($validatedData);

        return $this->jsend_success([
            'category' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->jsend_success(null);
    }
}
