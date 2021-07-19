<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FinancialAccountTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::resource('transactions', TransactionController::class)
        ->only('index', 'show', 'store');
    Route::get('/transactions/{year}/{month}',
        [TransactionController::class, 'listByMonthYear']);

    Route::resource('accounts', FinancialAccountController::class)
        ->only('index', 'show');

    Route::resource('accounts.transactions', FinancialAccountTransactionController::class)
        ->only('index');

    Route::get('/reports/assets', [ReportController::class, 'assetsReport']);
    Route::get('/reports/income-expense', [ReportController::class, 'incomeExpenseReport']);
});
