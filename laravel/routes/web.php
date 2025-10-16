<?php

use App\Http\Controllers\TopProductController;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\MonthlyRevenueController;
use App\Http\Controllers\TopCustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/import', [CsvImportController::class, 'import']);

// did not managed to set them into the /API routes, the file was not created by laravel at first.
Route::get('/report/topproducts/{top?}', [TopProductController::class, 'topProductsByRevenue']);
Route::get('/report/monthly-revenue/{year}', [MonthlyRevenueController::class, 'monthlyRevenue']);
Route::get('/report/top-customers/{top?}', [TopCustomerController::class, 'topCustomersByRevenue']);
