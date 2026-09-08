<?php

use App\Http\Controllers\Api\MemoApiController;
use App\Http\Controllers\Api\TaskApiController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json([
        'message' => 'API接続成功',
    ]);
});

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/categories', function () {
        return response()->json([
            'data' => Category::orderBy('id')->get(['id', 'name']),
        ]);
    });

    Route::apiResource('memos', MemoApiController::class);
    Route::apiResource('tasks', TaskApiController::class);
});