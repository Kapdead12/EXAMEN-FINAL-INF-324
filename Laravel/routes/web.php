<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\miControlador;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|


Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/',[miControlador::class,'lista'])->name('listado');

Route::get('/mivista', function () {
    return view('mivista');
});

/*
Route::get('/mivistaparametros', function () {
	$myname="Joaquin Kapa";
    return view('mivistaparametros', compact('myname'));
});

Route::get('/miControlador/{myname}',[miControlador::class,'index']);
*/

//PARA EDITAR
Route::get('/miControladorcito/edita/{ci}',[miControlador::class,'edita'])->name('editar');
Route::post('/miControladorcito/edita2/',[miControlador::class,'modificar'])->name('modificar');

//PARA ELIMINAR
Route::delete('/miControladorcito/eliminar/{ci}', [miControlador::class, 'eliminar'])->name('eliminar');

//PARA ADICIONAR
Route::get('/miControladorcito/adicionar/',[miControlador::class,'adicionar'])->name('adicionar');
Route::post('/miControladorcito/agregar', [miControlador::class, 'agregar'])->name('agregar');

