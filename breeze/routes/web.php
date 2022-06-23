<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Models\Marca;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';

/* CRUD de MARCAS */
Route::get('/marcas', [MarcaController::class, 'index'])->middleware(['auth'])->name('marcas');

Route::get('/marca/create', [MarcaController::class, 'create'])->middleware(['auth']);
Route::post('/marca/store', [MarcaController::class, 'store'])->middleware(['auth']);

Route::get('/marca/edit/{id}', [MarcaController::class, 'edit']);
Route::patch('/marca/update', [MarcaController::class, 'update']);

Route::get('/marca/delete/{id}', [MarcaController::class, 'confirm']);
Route::delete('/marca/destroy', [MarcaController::class, 'destroy']);


/* CRUD de CATEGORIAS */
Route::get('/categorias', [CategoriaController::class, 'index'])->middleware(['auth'])->name('categorias');

/* CRUD de PRODUCTOS */
Route::get('/productos', [ProductoController::class, 'index'])->middleware(['auth'])->name('productos');