<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Importer;

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

Route::get('/', [Importer::class, 'showLog'])->name("Importer.showLog");
Route::post('/add', [Importer::class, 'importData'])->name('Importer.importData');