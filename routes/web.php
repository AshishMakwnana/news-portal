<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\NewsController as PublicNewsController;
use App\Http\Controllers\NewsController;

Route::get('/', [PublicNewsController::class, 'index'])->name('home');
Route::get('/news/{slug}', [PublicNewsController::class, 'show'])->name('news.show');
Route::get('/category/{slug}', [PublicNewsController::class, 'category'])->name('category.show');
Route::get('/tag/{slug}', [PublicNewsController::class, 'tag'])->name('tag.show');
Route::post('/news/{news}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('news.comments.store');
Route::post('/admin/comments/{comment}/approve', [\App\Http\Controllers\CommentController::class, 'approve'])->middleware('auth')->name('admin.comments.approve');
Route::delete('/admin/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->middleware('auth')->name('admin.comments.destroy');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', NewsController::class);

    // Media library routes
    Route::get('media', [\App\Http\Controllers\MediaController::class, 'index'])->name('media.index');
    Route::post('media/upload', [\App\Http\Controllers\MediaController::class, 'upload'])->name('media.upload');
    Route::delete('media/{media}', [\App\Http\Controllers\MediaController::class, 'destroy'])->name('media.destroy');
});

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
