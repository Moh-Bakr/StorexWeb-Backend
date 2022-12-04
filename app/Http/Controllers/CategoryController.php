<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;

class CategoryController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get();
        
        return $this->createResponse('Categories ', $categories, Response::HTTP_OK);
    }


    public function store(StoreCategoryRequest $request)
    {
        $validated_data = $request->validated();
        $category = Category::create($validated_data);

        return $this->createResponse('Category created successfully', $category, Response::HTTP_CREATED);
    }

    public function update(StoreCategoryRequest $request, $id)
    {
        $category= Category::find($id);

        if ($category) {
            $validated_data = $request->validated();
            $category->update($validated_data);

            return $this->createResponse('Category updated successfully', $category, Response::HTTP_OK);
        }
        
        return $this->errorResponse('Category not found', Response::HTTP_NOT_FOUND);
    }


    public function destroy($id)
    {
        $category= Category::find($id);

        if ($category) {
            $category->delete();
            
            return $this->successResponse('Category Deleted Successfully', Response::HTTP_OK);
        }

        return $this->errorResponse('Category not found', Response::HTTP_NOT_FOUND);
    }
}