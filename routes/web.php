<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\NewsController as PublicNewsController;
use App\Http\Controllers\NewsController;

Route::get('/', [PublicNewsController::class, 'index'])->name('home');
Route::get('/news/{slug}', [PublicNewsController::class, 'show'])->name('news.show');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', NewsController::class);
});

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
