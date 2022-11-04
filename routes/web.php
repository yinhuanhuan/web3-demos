<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/wallets/create', function () {
    return view('wallets.create');
});

Route::get('/wallets/import', function () {
    return view('wallets.import');
});

Route::get('/wallets/address', function () {
    return view('wallets.address');
});

Route::get('/wallets/transaction', function () {
    return view('wallets.transaction');
});

Route::get('/wallets/token', function () {
    return view('wallets.token');
});

Route::get('/wallets/send_tokens', function () {
    return view('wallets.send_tokens');
});
