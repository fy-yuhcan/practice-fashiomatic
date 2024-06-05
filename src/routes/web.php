<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FashionController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/fashion/upload', [FashionController::class, 'showUploadForm'])->name('fashion.upload');
    Route::post('/fashion/upload', [FashionController::class, 'upload'])->name('fashion.upload.post');
    Route::get('/fashion/results', [FashionController::class, 'showResults'])->name('fashion.results');
    Route::post('/fashion/describe', [FashionController::class, 'describeImage'])->name('fashion.describe');
        
});
