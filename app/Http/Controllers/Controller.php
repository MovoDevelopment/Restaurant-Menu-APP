<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    static public function sendResponse($result, $message)
    {
        $data = [
            'success' => true,
            'data' => $result,
            'message' => $message,
            'errors' => null,
        ];
        return response()->json($data);
    }

    static public function sendError($errors, $code, $message = "validation_failed")
    {
        $res = [
            'success' => false,
            'data' => null,
            'message' => $message,
            'errors' => $errors,
        ];
        return response($res, $code);
    }
}
