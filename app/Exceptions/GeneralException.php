<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class GeneralException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'errors' => [
                'message' => $this->getMessage(),
            ]
        ], $this->code);
    }
}
