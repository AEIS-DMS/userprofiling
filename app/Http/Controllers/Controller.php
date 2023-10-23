<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Building success response
     * @param $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $message = 'success', $code = Response::HTTP_OK)
    {
        return \response()->json(['code' => $code = Response::HTTP_OK, 'message' => $message, 'data' => $data], $code);
    }


    public function successResponseWithoutData($message = '', $code = Response::HTTP_OK)
    {
        return \response()->json(['code' => $code = Response::HTTP_OK, 'message' => $message], Response::HTTP_OK);
    }

    public function errorResponse($message, $code)
    {
        return \response()->json(['code' => $code, 'message' => $message], Response::HTTP_OK);
    }
}
