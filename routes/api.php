<?php

use App\Http\Controllers\Api\Admin\SellerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
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

Route::prefix('v1')->group(function (){

    // authentications
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:api'])->group(function (){
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('/user',[AuthController::class, 'user']);

        Route::prefix('admin')->middleware('can:admin_all')->group(function (){
            Route::apiResource('sellers', SellerController::class)->parameters([
                'sellers' => 'user'
            ]);
        });

        Route::prefix('sellers')->middleware('can:seller_all')->group(function (){
            Route::apiResource('products', ProductController::class);
        });

        Route::get('/stores/nearby',[CustomerController::class, 'nearbyStores']);
        Route::get('/products/{product}/buy',[CustomerController::class, 'buyProduct']);



    });
});

