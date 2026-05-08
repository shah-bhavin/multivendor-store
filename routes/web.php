<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\Categories;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::livewire('/', 'store.home-page');
Route::livewire('/products','store.product-list');
Route::livewire('/products/{product}','store.product-details');