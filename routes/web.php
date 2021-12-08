<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\AuthController;

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
Route::post('/entrar', [AuthController::class, 'auth'])->name('auth.user');

Route::get('/sair', [AuthController::class, 'logout'])->name('site.logout');

// routes with middleware
Route::group(['middleware' => 'auth'], function () {
  Route::get('/usuarios', [UserController::class, 'index'])->name('site.auth.users.index');
  Route::get('/clientes', [ClientsController::class, 'index'])->name('site.auth.clients.index');
  Route::get('/clientes/cadastrar', [ClientsController::class, 'create'])->name('site.auth.clients.form');
  Route::post('/clientes/cadastrar', [ClientsController::class, 'store'])->name('site.auth.clients.store');
  Route::get('/clientes/{id}/editar', [ClientsController::class, 'edit'])->name('site.auth.clients.edit');
  Route::put('/clientes/{id}/editar', [ClientsController::class, 'update'])->name('site.auth.clients.update');
  Route::delete('/clientes/{id}/excluir', [ClientsController::class, 'destroy'])->name('site.auth.clients.destroy');
});

