<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Category;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;

class MovieController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $movies = Movie::orderBy('id', 'asc')->get();

        return $this->createResponse('Movies ', $movies, Response::HTTP_OK);
    }

    public function store(StoreMovieRequest $request)
    {
        $validated_data = $request->validated();

        $category = Category::find($validated_data['category_id']);

        if (!$category) {
            return $this->errorResponse('Category not found', Response::HTTP_NOT_FOUND);
        }

        $uploadedFileUrl = $request->file('image')->storeOnCloudinary('media/movies');
        $validated_data['image'] = $uploadedFileUrl->getSecurePath();
        $public_id = $uploadedFileUrl->getPublicId();
        $result= explode('/', $public_id);
        $validated_data['image_public_id'] = $result[2];

        $movie = Movie::create($validated_data);

        return $this->createResponse('Movie created successfully', $movie, Response::HTTP_CREATED);
    }

    public function update(UpdateMovieRequest $request, $id)
    {
        $movie = Movie::find($id);
        $validated_data = $request->validated();
        $category = Category::find($validated_data['category_id']);


        if (!$category) {
            return $this->errorResponse('Category not found', Response::HTTP_NOT_FOUND);
        }

        if ($request->hasFile('image')) {
            Cloudinary()->destroy($movie->image_public_id);
            $uploadedFileUrl = $request->file('image')->storeOnCloudinary('media/movies');
            $validated_data['image'] = $uploadedFileUrl->getSecurePath();
            $public_id = $uploadedFileUrl->getPublicId();
            $result= explode('/', $public_id);
            $validated_data['image_public_id'] = $result[2];
        }
        if ($movie) {
            $movie->update($validated_data);

            return $this->createResponse('Movie updated successfully', $movie, Response::HTTP_OK);
        }

        return $this->errorResponse('Movie not found', Response::HTTP_NOT_FOUND);
    }

    public function searchMovies(Request $request)
    {
        if ($request->title) {
            $movie = Movie::where('title', $request->title)->get();

            return $this->search($movie);
        }
        if ($request->category_id) {
            $movie = Movie::where('category_id', $request->category_id)->get();

            return $this->search($movie);
        }
        if ($request->rate) {
            $movie = Movie::where('rate', $request->rate)->get();

            return $this->search($movie);
        }
    }
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if ($movie && !file_exists($movie->image)) {
            Cloudinary()->destroy($movie->image_public_id);
            $movie->delete();
            return $this->successResponse('Movie Deleted Successfully', Response::HTTP_OK);
        }

        return $this->errorResponse('Movie not found', Response::HTTP_NOT_FOUND);
    }
}