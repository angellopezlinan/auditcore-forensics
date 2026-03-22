<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

// Bóveda Privada (Vault) - Rutas Seguras
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vault/invoices/{invoice}/download', [\App\Http\Controllers\VaultController::class, 'downloadInvoice'])
        ->name('vault.invoices.download');
});
