<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TareaCampoController;

use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\IntegridadTablasController;
use App\Http\Controllers\SequenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::post('/login', [LoginController::class, 'login'])->name("login");
Route::post('/register/user', [LoginController::class, 'register'])->name("register");
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/connect', [DatabaseController::class, 'showConnectionForm'])->name('show.connection.form');
    Route::post('/connect', [DatabaseController::class, 'connectDatabase'])->name('connect.database');
    Route::get('/showMysql/{tableName}', [DatabaseController::class, 'showTableMysql'])->name('show.tableMysql');
    Route::get('/showSQL/{tableName}', [DatabaseController::class, 'showTableSQL'])->name('show.tableSQL');
    Route::get('/eliminar-registro/{id}', [DatabaseController::class, 'eliminarRegistro']);
    Route::get('/showTables', [DatabaseController::class, 'showAllTables'])->name('show.tables');
    Route::get('/table/structure/{tableName}', [DatabaseController::class, 'showTableStructure'])->name('table.structure');


});

Route::resource('tareacampo',TareaCampoController::class);
Route::get('/tareacampos/{id}/{state}', [TareaCampoController::class, 'analizar'])->name('analizar.campo');
Route::get('/tareacamposs/{id}', [TareaCampoController::class, 'pdf'])->name('campo.pdf');
Route::get('/cancelar', [TareaCampoController::class, 'cancelar'])->name('campo.cancelar');


//Tablas
Route::get('excepcion/integridad-tablas/index',[IntegridadTablasController::class,'index'])->name('integridadtablas.index');
Route::get('excepcion/integridad-tablas/analisis',[IntegridadTablasController::class,'analysis'])->name('integridadtablas.analysis');
Route::get('excepcion/integridad-tablas/cancelar',[IntegridadTablasController::class,'cancelar'])->name('integridadtablas.cancelar');


Route::resource('secuencialidad',SequenceController::class);