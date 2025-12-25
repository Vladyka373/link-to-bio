<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LinkController; // 📝 Импортируем контроллер ссылок


// 📝 Публичная страница профиля (БЕЗ авторизации)
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.public');

// 📝 Роут для отображения аватара из БД (БЕЗ авторизации)
Route::get('/profile/avatar/{id}', [ProfileController::class, 'avatar'])->name('profile.avatar');

// 📝 Маршруты для ссылок
Route::middleware('auth')->group(function () {
    Route::resource('links', LinkController::class);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
