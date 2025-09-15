<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ReceivableController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('budgets', BudgetController::class)->except(['show']);
    Route::get('budgets/{budget}/transactions-snippet', [BudgetController::class, 'transactionsSnippet'])->name('budgets.transactionsSnippet');
    Route::get('budgets/{budget}/edit-snippet', [BudgetController::class, 'editSnippet'])->name('budgets.editSnippet');
    Route::post('budgets/{budget}/select', [BudgetController::class, 'select'])->name('budgets.select');
    Route::get('transactions/add-snippet', [TransactionController::class, 'addSnippet'])->name('transactions.add-snippet');
    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class);
    Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::get('transactions/{transaction}/edit-snippet', [TransactionController::class, 'editSnippet'])->name('transactions.editSnippet');
    // Debts routes
    Route::get('debts', [DebtController::class, 'index'])->name('debts.index');
    Route::get('debts/create', [DebtController::class, 'create'])->name('debts.create');
    Route::get('debts/add-snippet', [DebtController::class, 'addSnippet'])->name('debts.add-snippet');
    Route::post('debts', [DebtController::class, 'store'])->name('debts.store');
    Route::get('debts/{debt}', [DebtController::class, 'show'])->name('debts.show');
    Route::get('debts/{debt}/edit-snippet', [DebtController::class, 'editSnippet'])->name('debts.edit-snippet');
    Route::put('debts/{debt}', [DebtController::class, 'update'])->name('debts.update');
    Route::delete('debts/{debt}', [DebtController::class, 'destroy'])->name('debts.destroy');
    
    // Debt Payments routes
    Route::post('debt-payments', [App\Http\Controllers\DebtPaymentController::class, 'store'])->name('debt-payments.store');
    Route::put('debt-payments/{debtPayment}', [App\Http\Controllers\DebtPaymentController::class, 'update'])->name('debt-payments.update');
    Route::delete('debt-payments/{debtPayment}', [App\Http\Controllers\DebtPaymentController::class, 'destroy'])->name('debt-payments.destroy');
    
    // Receivables routes
    Route::get('receivables', [ReceivableController::class, 'index'])->name('receivables.index');
    Route::get('receivables/create', [ReceivableController::class, 'create'])->name('receivables.create');
    Route::get('receivables/add-snippet', [ReceivableController::class, 'addSnippet'])->name('receivables.add-snippet');
    Route::post('receivables', [ReceivableController::class, 'store'])->name('receivables.store');
    Route::get('receivables/{receivable}/edit-snippet', [ReceivableController::class, 'editSnippet'])->name('receivables.edit-snippet');
    Route::put('receivables/{receivable}', [ReceivableController::class, 'update'])->name('receivables.update');
    Route::delete('receivables/{receivable}', [ReceivableController::class, 'destroy'])->name('receivables.destroy');
});

require __DIR__.'/auth.php';
