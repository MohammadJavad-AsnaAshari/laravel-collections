<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::name('api.')->group(function () {
    // Articles Routes!
    Route::prefix('articles')->group(function () {
        Route::get('count', [ArticleController::class, 'count']);
        Route::get('count-by', [ArticleController::class, 'countBy']);

        Route::get('max', [ArticleController::class, 'max']);
        Route::get('min', [ArticleController::class, 'min']);
        Route::get('median', [ArticleController::class, 'median']);
        Route::get('mode', [ArticleController::class, 'mode']);
        Route::get('random', [ArticleController::class, 'random']);
        Route::get('sum', [ArticleController::class, 'sum']);

        Route::prefix('where')->group(function () {
            Route::get('/', [ArticleController::class, 'where']);
            Route::get('multi', [ArticleController::class, 'whereMulti']);
            Route::get('closure', [ArticleController::class, 'whereClosure']);
            Route::get('strict', [ArticleController::class, 'whereStrict']);
            Route::get('between', [ArticleController::class, 'whereBetween']);
            Route::get('null', [ArticleController::class, 'whereNull']);
            Route::get('date', [ArticleController::class, 'whereDate']);
            Route::get('day', [ArticleController::class, 'whereDay']);
            Route::get('month', [ArticleController::class, 'whereMonth']);
            Route::get('year', [ArticleController::class, 'whereYear']);
            Route::get('time', [ArticleController::class, 'whereTime']);
        });
        Route::get('filter', [ArticleController::class, 'filter']);
        Route::get('filter/closure', [ArticleController::class, 'filterClosure']);
        Route::get('reject', [ArticleController::class, 'reject']);
        Route::get('reject/closure', [ArticleController::class, 'rejectClosure']);

        Route::get('contains', [ArticleController::class, 'contains']);
        Route::get('except', [ArticleController::class, 'except']);
        Route::get('only', [ArticleController::class, 'only']);
        Route::get('select', [ArticleController::class, 'select']);

        Route::get('map', [ArticleController::class, 'map']);
        Route::get('map-with-key', [ArticleController::class, 'mapWithKey']);

        Route::get('pluck', [ArticleController::class, 'pluck']);
        Route::get('key-by', [ArticleController::class, 'keyBy']);

        Route::get('push', [ArticleController::class, 'push']);
        Route::get('put', [ArticleController::class, 'put']);
        Route::get('pop', [ArticleController::class, 'pop']);
        Route::get('forget', [ArticleController::class, 'forget']);
        Route::get('shift', [ArticleController::class, 'shift']);

        Route::get('concat', [ArticleController::class, 'concat']);
        Route::get('zip', [ArticleController::class, 'zip']);

        Route::get('collapse', [ArticleController::class, 'collapse']);
        Route::get('split', [ArticleController::class, 'split']);

        Route::get('sort', [ArticleController::class, 'sort']);
        Route::get('sort-desc', [ArticleController::class, 'sortDesc']);
        Route::get('sort-by', [ArticleController::class, 'sortBy']);
        Route::get('sort-by-desc', [ArticleController::class, 'sortByDesc']);
        Route::get('sort-keys', [ArticleController::class, 'sortKeys']);
        Route::get('sort-keys-desc', [ArticleController::class, 'sortKeysDesc']);
    });
    Route::apiResource('articles', ArticleController::class);

    // Users Routes!
    Route::prefix('users')->group(function () {
        Route::get('where-in', [UserController::class, 'whereIn']);
        Route::get('where-not-in', [UserController::class, 'whereNotIn']);
    });
});
