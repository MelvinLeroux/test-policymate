<?php

use App\Http\Controllers\CsvImportController;
use Illuminate\Support\Facades\Route;

Route::get('/import', [CsvImportController::class, 'import']);

