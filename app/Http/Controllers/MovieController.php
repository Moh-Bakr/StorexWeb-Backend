<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Category;
use App\Models\Movie;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();

        return response([
            'status' => 'success',
            'message' => $movies,
        ], Response::HTTP_OK);
    }

    public function store(StoreMovieRequest $request)
    {
        $validated_data = $request->validated();

        $category = Category::find($validated_data['category_id']);

        if (!$category) {
            return response([
                'status' => 'failed',
                'message' => 'Category not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $uploadedFileUrl = $request->file('image')->storeOnCloudinary('media/movies');
        $validated_data['image'] = $uploadedFileUrl->getSecurePath();
        $validated_data['image_public_id'] = $uploadedFileUrl->getPublicId();
        $movie = Movie::create($validated_data);

        return response([
            'status' => 'success',
            'message' => 'Movie created successfully',
            'movie' => $movie,
        ], Response::HTTP_CREATED);
    }

    public function update(StoreMovieRequest $request, $id)
    {
        $movie = Movie::find($id);
        $validated_data = $request->validated();
        $category = Category::find($validated_data['category_id']);

        if ($movie && !file_exists($movie->image)) {
            Cloudinary()->destroy($movie->image_public_id);

            $uploadedFileUrl = $request->file('image')->storeOnCloudinary('media/movies');
            $validated_data['image'] = $uploadedFileUrl->getSecurePath();
            $validated_data['image_public_id'] = $uploadedFileUrl->getPublicId();

            $movie->update($validated_data);

            return response([
                'status' => 'success',
                'message' => 'Movie updated successfully',
                'category' => $movie,
            ], Response::HTTP_OK);
        }

        return response([
            'status' => 'failed',
            'message' => 'Movie not found',
        ], Response::HTTP_NOT_FOUND);
    }
    
    public function show(Request $request)
    {
        if ($request->has('category_id')) {
            
            $movies = Movie::where('category_id', $request->category_id)->get();
            return response([
                'status' => 'success',
                'message' => $movies,
            ], Response::HTTP_OK);
        }
        if ($request->has('title')) {
            $movies = Movie::where('title', 'like', '%' . $request->title . '%')->get();
            return response([
                'status' => 'success',
                'message' => $movies,
            ], Response::HTTP_OK);
        }
        if ($request->has('rate')) {
            $movies = Movie::where('rate', $request->rate)->get();
            
            return response([
                'status' => 'success',
                'message' => $movies,
            ], Response::HTTP_OK);
        }
    }
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if ($movie && !file_exists($movie->image)) {
            Cloudinary()->destroy($movie->image_public_id);
            $movie->delete();
            return response([
                'status' => 'success',
                'message' => 'Movie deleted successfully',
            ], Response::HTTP_OK);
        }

        return response([
            'status' => 'failed',
            'message' => 'Movie not found',
        ], Response::HTTP_NOT_FOUND);
    }
}