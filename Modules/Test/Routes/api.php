<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Modules\Test\\',
    'prefix' => 'test',
], function ($router) {
    Route::get('/app', function (Request $request) {
        return response()->json([
            'message' => 'Laravel Modules test works!'
        ]);
    }); //->middleware('auth:api');
});
