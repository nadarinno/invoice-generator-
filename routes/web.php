<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');

Route::post('/calculate', [InvoiceController::class, 'calculate'])->name('invoice.calculate');