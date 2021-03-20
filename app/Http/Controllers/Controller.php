<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getResponse($status_code, $status, $message, $data) {
        return response()->json([
            'status_code' => (int)$status_code,
            'status' => (string)$status,
            'message' => (string)$message,
            'data' => $data
        ], $status_code);
    }
}
