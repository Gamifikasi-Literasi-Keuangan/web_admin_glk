<?php

use Illuminate\Support\Facades\Route;

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect('/login');
});

// Rute Halaman Login
Route::get('/login', function () {
    return view('login'); // Memanggil file resources/views/login.blade.php
})->name('login');

// Rute Dashboard
Route::get('/dashboard', function () {
    return view('pages.dashboard'); // Memanggil file resources/views/pages/dashboard.blade.php
})->name('dashboard');

// Rute Admin Players
Route::get('/admin/players', function () {
    return view('pages.players');
})->name('admin.players');

// Rute Admin Content (Paket 2)
Route::get('/admin/content', function () {
    return view('pages.content');
})->name('admin.content');
Route::get('/admin/settings', function () {
    return view('pages.settings');
})->name('admin.settings');

// Halaman Operasi Game (Paket 3)
Route::get('/admin/games', function () {
    return view('pages.games');
})->name('admin.games');
Route::get('/admin/analytics', function () {
    return view('pages.analytics');
})->name('admin.analytics');