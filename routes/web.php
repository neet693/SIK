<?php

use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoanController::class, 'index']);

Route::post('/submit-loan', [LoanController::class, 'submitLoan'])->name('loan.submit');
