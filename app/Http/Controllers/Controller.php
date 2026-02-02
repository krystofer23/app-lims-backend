<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    public function sendSuccess($message): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function sendError($message, $code = 404): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $code);
    }

    public function sendResponse($data, $message): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }
}
