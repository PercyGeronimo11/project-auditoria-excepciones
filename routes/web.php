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

/* Route::get('/register', function () {
    return view('register');
}); */

Route::post('/login', [LoginController::class, 'login'])->name("login");

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
    Route::get('/table/structure/{tableName}', [DatabaseController::class, 'showTableStructure'])->name('table.structure');
    Route::post('/execute-query', [DatabaseController::class, 'executeQuery'])->name('execute.query');
    Route::get('/query-form', [DatabaseController::class, 'showConnectionQuery'])->name('query.form');
    Route::get('/consultas', [DatabaseController::class, 'listarConsultas'])->name('consultas.listar');
    Route::get('consultas/{id}/resultados',[DatabaseController::class, 'showConsultaResult'])->name('consultas.resultados');
    Route::get('consultas/{id}/editar',[DatabaseController::class, 'editConsulta'])->name('consultas.edit');
    Route::put('consultas/{id}',[DatabaseController::class, 'updateConsulta'])->name('consultas.update');
    Route::delete('/consultas/{id}', [DatabaseController::class, 'destroyConsulta'])->name('consultas.destroy');
    Route::get('/disconnect-database', [DatabaseController::class, 'disconnectDatabase'])->name('disconnect.database');



});

Route::resource('tareacampo',TareaCampoController::class);
Route::get('/tareacampos/{id}/{state}', [TareaCampoController::class, 'analizar'])->name('analizar.campo');
Route::get('/tareacamposs/{id}', [TareaCampoController::class, 'pdf'])->name('campo.pdf');
Route::get('/cancelar', [TareaCampoController::class, 'cancelar'])->name('campo.cancelar');


//Tablas
Route::get('excepcion/integridad-tablas/create',[IntegridadTablasController::class,'create'])->name('integridadtablas.create');
Route::post('excepcion/integridad-tablas/store',[IntegridadTablasController::class,'store'])->name('integridadtablas.store');
Route::get('excepcion/integridad-tablas/delete/{id}',[IntegridadTablasController::class,'delete'])->name('integridadtablas.delete');
Route::get('excepcion/integridad-tablas/index',[IntegridadTablasController::class,'index'])->name('integridadtablas.index');
Route::get('excepcion/integridad-tablas/analisis/{id}',[IntegridadTablasController::class,'analysis'])->name('integridadtablas.analysis');
Route::get('excepcion/integridad-tablas/cancelar',[IntegridadTablasController::class,'cancelar'])->name('integridadtablas.cancelar');
Route::post('excepcion/integridad-tablas/exportapdf/{id}',[IntegridadTablasController::class,'exportarPdf'])->name('integridadtablas.exportpdf');


Route::resource('secuencialidad',SequenceController::class);

Route::get('excepcion/secuencialidad/pdf/{id}',[SequenceController::class,'generatepdf'])->name('generatepdf');
Route::get('excepcion/secuencialidad/use/{id}',[SequenceController::class,'useRegister'])->name('useRegister');
Route::get('excepcion/create',[SequenceController::class,'create'])->name('createSecuencialidad');
Route::get('excepcion/delete/{id}',[SequenceController::class,'eliminar'])->name('deleteSecuencialidad');
Route::get('users',[LoginController::class,'list'])->name('listUsers');
Route::get('Users',[LoginController::class,'list2'])->name('listUsers_inhabil');
Route::get('users/create',[LoginController::class,'create'])->name('createUser');
Route::post('/register/user', [LoginController::class, 'register'])->name("register");
Route::get('user/delete/{id}',[LoginController::class,'delete'])->name('deleteUser');
Route::get('user/edit/{id}',[LoginController::class,'edit'])->name('editUser');
Route::put('user/update/{id}',[LoginController::class,'update'])->name('updateUser');