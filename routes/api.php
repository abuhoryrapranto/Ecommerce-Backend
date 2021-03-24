<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
    return $request->user();
});


Route::post('/save-user', [PublicController::class, 'saveUser']);
Route::post('/login', [PublicController::class, 'login']);
Route::post('/logout', [PublicController::class, 'logout']);

Route::prefix('v1')->group(function () {
    Route::get('/feature-products', [ProductController::class, 'getAllFeatureProducts']);
});
