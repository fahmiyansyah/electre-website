<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElectreController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/electre1', function () {
    return view('electre1');
});
Route::get('/electre2', function () {
    return view('electre2');
});

Route::get('/electre3', function () {
    return view('electre3');
});

Route::get('/1', function () {
    return view('electre1oto');
});



