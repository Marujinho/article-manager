<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Preference\PreferenceController;
use App\Http\Controllers\Auth\UserAuthController;

Route::get('/test-articles', [ArticleController::class, 'testArticles']);
Route::post('user/register', [UserAuthController::class, 'register']);
Route::post('user/login', [UserAuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('user')->group(function () {
        Route::middleware('auth:sanctum')->post('/logout', [UserAuthController::class, 'logout'])->name('logout');
        Route::post('/password-reset', [UserAuthController::class, 'resetPassword'])->name('password.reset');
    });

    Route::prefix('article')->group(function () {
        Route::get('/list', [ArticleController::class, 'list'])->name('article.list');
        Route::get('/view-article/{article}', [ArticleController::class, 'view'])->name('article.view');
        Route::get('/filtered-view', [ArticleController::class, 'filterArticleByWord'])->name('article.filtered-view');
        Route::get('/prefered', [ArticleController::class, 'getPreferedArticles'])->name('article.view-prefered');
    });

    Route::prefix('preference')->group(function () {
        Route::post('create', [PreferenceController::class, 'create'])->name('preference.create');
        Route::get('list', [PreferenceController::class, 'list'])->name('preference.list');
        Route::put('update/{preference}', [PreferenceController::class, 'update'])->name('preference.update');
        Route::delete('delete/{preference}', [PreferenceController::class, 'delete'])->name('preference.delete');
    });
});