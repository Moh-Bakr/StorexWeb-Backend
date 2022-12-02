<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response([
            'status' => 'success',
            'message' => $categories,
            ], Response::HTTP_OK);
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated_data = $request->validated();
        $category = Category::create($validated_data);

        return response([
             'status' => 'success',
             'message' => 'Category created successfully',
             'category' => $category,
            ], Response::HTTP_CREATED);
    }

    public function update(StoreCategoryRequest $request, $id)
    {
        $category= Category::find($id);

        if ($category) {
            $validated_data = $request->validated();
            $category->update($validated_data);
            
            return response([
                'status' => 'success',
                'message' => 'Category updated successfully',
                'category' => $category,
            ], Response::HTTP_OK);
        }
        
        return response([
            'status' => 'failed',
            'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
    }


    public function destroy($id)
    {
        $category= Category::find($id);
        
        if ($category) {
            $category->delete();
            
            return response([
                'status' => 'success',
                'message' => 'Category deleted successfully',
            ], Response::HTTP_OK);
        }
        
        return response([
            'status' => 'failed',
            'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
    }
}