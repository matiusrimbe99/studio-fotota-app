<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\PacketController;
use App\Http\Controllers\Api\StudioController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [UserController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'checkRole:2'])->group(function () {
    Route::apiResource('/packets', PacketController::class);
    Route::apiResource('/customers', CustomerController::class);
    Route::apiResource('/studios', StudioController::class);
    Route::apiResource('/galleries', GalleryController::class);
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{brand}', [BrandController::class, 'show']);
    Route::match (['put', 'patch'], '/brands/{brand}', [BrandController::class, 'update']);
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/{contact}', [ContactController::class, 'show']);
    Route::match (['put', 'patch'], '/contacts/{contact}', [ContactController::class, 'update']);
});
