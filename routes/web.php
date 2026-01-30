<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\NewsController as PublicNewsController;
use App\Http\Controllers\NewsController;

Route::get('/', [PublicNewsController::class, 'index'])->name('home');
Route::get('/news/{slug}', [PublicNewsController::class, 'show'])->name('news.show');
Route::post('/news/{news}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('news.comments.store');
Route::post('/admin/comments/{comment}/approve', [\App\Http\Controllers\CommentController::class, 'approve'])->middleware('auth')->name('admin.comments.approve');
Route::delete('/admin/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->middleware('auth')->name('admin.comments.destroy');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', NewsController::class);
});

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
