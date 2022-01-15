<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\BlogController;

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



Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::get('blogs', [BlogController::class, 'index']);
    Route::post('add-blog', [BlogController::class, 'store']);
    Route::get('edit-blog/{id}', [BlogController::class, 'edit']);
    Route::post('update-blog/{id}', [BlogController::class, 'update']);
    Route::delete('delete-blog/{id}', [BlogController::class, 'destroy']);

});
//Route::get('blogs', [BlogController::class, 'index']);
//Route::post('update-blog/{id}', [BlogController::class,'update']);
Route::post('login', [AuthController::class, 'login']);
Route::get('all-blogs', [BlogController::class, 'allblogs']);
Route::get('single-blog/{slug} ', [BlogController::class, 'singleblog']);
