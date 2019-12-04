<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function() {

	Route::get('/error', function () {
	    return response()->json([
            'status' => 'failed',
            'error' => [
                'message' => 'token mismatch',
                'status_code' => 401,
                'token' => csrf_token()
            ],
        ]);
	});

	// Route::post('/login', 'ClientController@login');
	// Route::get('/test', 'ClientController@getTest')->middleware('auth.api');

	// Route::post('/post_test', 'ClientController@postTest')->middleware('auth.api');
});
