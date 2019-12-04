<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class ClientController extends ApiController
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            return $this->respond(['userId' => Auth::id()]);

        } else {
            return $this->respondWithError('Authorization failed', 401);
        }
    }
}
