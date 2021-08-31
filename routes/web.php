<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeLoanController;
use App\Http\Controllers\ProgramPayController;
use App\Http\Controllers\ProgramSuperController;
use App\Http\Controllers\InvestPersonalController;
use App\Http\Controllers\MonthlyNetworthController;
use App\Http\Controllers\LongTermInvestmentController;
use App\Http\Controllers\Program5YRNetworthController;

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
    return view('auth.login');
});

Auth::routes();

Route::get('/Landing-Page', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/chart', function (){
    return view('dashboard.dashboard.chart', );
})->name('chart.show');




Route::get('/Landing-page', function(){
    return view('landing');
})->name('landingPage');

Route::middleware(['auth', 'check', 'auth.basic'])->group(function () {
    Route::get('/Home-Loan', [HomeLoanController::class, 'show'])->name('homeloan.show');

    Route::get('/Invest-Personal', [InvestPersonalController::class, 'show'])->name('investpersonal.show');

    Route::get('/Program-Super', [ProgramSuperController::class, 'show'])->name('programsuper.show');

    Route::get('/Long-Term-Investement', [LongTermInvestmentController::class, 'show'])->name('longterminvestment.show');

    Route::get('/Program-5-Year-Networth', [Program5YRNetworthController::class, 'show'])->name('5yrnetworth.show');

    Route::get('/Program-Pay', [ProgramPayController::class, 'show'])->name('programpay.show');

    Route::get('/Monthly-networth', [MonthlyNetworthController::class, 'show'])->name('monthlynetworth.show');
});
