<?php

use App\Http\Controllers\api\UserApiController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware(['auth:api',])->group(function () {

    Route::get('/user', [UserApiController::class, 'UserGet'])->name('UserGet');
    Route::post('/user/update/', [UserApiController::class, 'UserUpdate'])->name('UserUpdate');
    Route::post('/user/logout/', [UserApiController::class, 'UserLogout'])->name('UserLogout');
});

Route::post('/user/register', [UserApiController::class, 'UserAdd'])->name('UserAdd');
Route::get('/user/login', [UserApiController::class, 'UserLoginView'])->name('UserLoginView');
Route::post('/user/login', [UserApiController::class, 'UserLogin'])->name('UserLogin')->middleware('guest:api');
Route::delete('/user/delete/{user:id}', [UserApiController::class, 'UserDelete'])->name('UserDelete');
