<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientsController;

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
    return redirect('/entrar');
});

Route::get('/entrar', [UserController::class, 'login'])->name('site.login');
Route::get('/registrar-se', [UserController::class, 'register'])->name('site.register');

Route::post('/registrar-se', [UserController::class, 'store'])->name('auth.register');
Route::post('/entrar', [UserController::class, 'auth'])->name('auth.user');

// routes with middleware
Route::group(['middleware' => 'auth'], function () {
  Route::get('/usuarios', [UserController::class, 'index'])->name('site.auth.users.index');
  Route::get('/clientes', [ClientsController::class, 'index'])->name('site.auth.clients.index');
  // Route::get('/clientes/cadastrar', [ClientsController::class, 'create'])->name('site.clients.create');
  // Route::post('/clientes/cadastrar', [ClientsController::class, 'store'])->name('site.clients.store');
  // Route::get('/clientes/{id}/editar', [ClientsController::class, 'edit'])->name('site.clients.edit');
  // Route::put('/clientes/{id}/editar', [ClientsController::class, 'update'])->name('site.clients.update');
  // Route::get('/clientes/{id}/excluir', [ClientsController::class, 'destroy'])->name('site.clients.destroy');
});

