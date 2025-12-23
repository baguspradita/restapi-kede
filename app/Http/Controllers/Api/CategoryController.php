<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $categories = Category::where('is_active', true)->get();
            return $this->successResponse($categories, 'Categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve categories: ' . $e->getMessage(), [], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return $this->successResponse($category, 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Category not found');
        }
    }

    public function products($id)
    {
        try {
            $category = Category::findOrFail($id);
            $products = $category->products()
                ->where('is_available', true)
                ->paginate(request('limit', 10));

            return $this->paginatedResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Category not found');
        }
    }
}
