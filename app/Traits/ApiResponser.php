<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    protected function successResponse($message, $code)
    {
        return response([
            'status' => 'success',
            'message' => $message,
            ], $code);
    }
    protected function createResponse($message, $data, $code)
    {
        return response([
            'status' => 'success',
            'message' => $message,
            'data'=> $data,
            ], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response([
            'status' => 'failed',
            'message' => $message,
            ], $code);
    }

    public function search($data)
    {
        if ($data->first()) {
            return $this->createResponse('Movies ', $data, Response::HTTP_OK);
        }
        return $this->errorResponse('Movie not found', Response::HTTP_NOT_FOUND);
    }
}