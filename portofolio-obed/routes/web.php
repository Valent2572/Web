<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortofolioController;

Route::get('/', [PortofolioController::class, 'home'])->name('home');
Route::get('/service', [PortofolioController::class, 'service'])->name('service');
Route::get('/about', [PortofolioController::class, 'about'])->name('about');
Route::get('/contact', [PortofolioController::class, 'contact'])->name('contact');
