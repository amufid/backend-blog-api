<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('posts')->get();
        return response()->json([
            'success' => true,
            'message' => 'List of Categories',
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $errors = ValidationHelper::validateDataCategory($request->all());

        if ($errors) {
            return response()->json([
                'success' => false,
                'errors' => $errors,
            ], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Create Category Successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('posts')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Category',
            'data' => CategoryResource::make($category),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $errors = ValidationHelper::validateDataCategory($request->all());

        if ($errors) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ]);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'errors' => 'Category not found',
            ]);
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Update Category Successfully',
            'data' => CategoryResource::make($category),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'errors' => 'Category not found',
            ]);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delete Category Successfully',
        ]);
    }
}
