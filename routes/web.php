<?php

use App\Http\Controllers\MercadoLivreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/product/create',[ProductController::class, 'create'])
->middleware(['auth', 'verified'])
->name('product.create');

Route::post('/product', [ProductController::class, 'store'])
->middleware(['auth', 'verified'])
->name('product.store');

Route::get('/categories/{category_id}/attributes', [ProductController::class, 'getCategoryAttributes']);

Route::get('/', function () {
    return view('welcome');  
})->middleware(['auth', 'verified']);

Route::get('/redirect', [MercadoLivreController::class, 'getAccessToken'])
->middleware(['auth', 'verified'])
->name('access_token.get');

Route::get('/auth/mercadolivre', [MercadoLivreController::class, 'redirectToProvider'])
->middleware(['auth', 'verified'])
->name('code.get');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
