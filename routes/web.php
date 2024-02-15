<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\OrderController as OdController;

// use App\Http\Controllers\Payment\SslController;

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

Route::redirect('/', 'login');

Route::get('get-token', [ProductController::class,'testa']);

Route::get('login', function () {
    return view('admin.login');
});

Route::post('login', [AdminLoginController::class, 'login'])->name('login');
Route::view('forgot-password', 'admin.forgot_password')->name('forgot-password');
Route::post('send-reset-mail', [AdminLoginController::class, 'resetMail'])->name('send-reset-mail');
Route::get('enter-password', [AdminLoginController::class,'enterPassword'])->name('reset.password.enter');
Route::post('enter-password', [AdminLoginController::class,'store'])->name('reset.password.enter');

Route::get('ssl-commerz/{order_id}',[OdController::class,'sslCommerz'])->name('payment.ssl');
Route::post('ssl-success',[OdController::class,'sslCommerzSuccess'])->name('ssl.success');
Route::post('ssl-failed',[OdController::class,'sslCommerzFailed'])->name('ssl.failed');
Route::post('ssl-cancel',[OdController::class,'sslCommerzCancel'])->name('ssl.cancel');

Route::get('invoice',[OdController::class,'invoiceToMail'])->name('invoice');
