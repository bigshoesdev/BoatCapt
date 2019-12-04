<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    private $statusCode = 200;

    public function respond($data, $headers = [])
    {
    	return response()->json($data, $this->getStatusCode())->withHeaders($headers);
    }

    public function getStatusCode()
    {
    	return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
    	$this->statusCode = $statusCode;
    }

    public function respondWithError($message, $statusCode = 0)
    {
        return $this->respond([
            'status' => 'failed',
            'error' => [
                'message' => $message,
                'status_code' => ($statusCode) ? $statusCode : $this->getStatusCode(),
            ],
        ]);
    }
}
