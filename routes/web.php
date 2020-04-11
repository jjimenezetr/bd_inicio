<?php

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
	return view('Auth\login');
    //return view('welcome');
});

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('lista_usuarios', 'UsuarioController@lista_usuarios')->name('lista_usuarios');
Route::any('formulario_usuario/{id}', 'UsuarioController@formulario_usuario')->name('formulario_usuario');
Route::any('editar_usuario/{id}', 'UsuarioController@editar_usuario')->name('editar_usuario');
Route::any('nuevo_usuario/{id}', 'UsuarioController@nuevo_usuario')->name('nuevo_usuario');
Route::get('eliminar_usuario/{id}', 'UsuarioController@eliminar_usuario')->name('eliminar_usuario');

Route::get('lista_roles', 'RolController@lista_roles')->name('lista_roles');
Route::post('guardar_rol', 'RolController@guardar_rol')->name('guardar_rol');
Route::get('eliminar_roles/{id}', 'RolController@eliminar_roles')->name('eliminar_roles');

Route::get('lista_personas', 'PersonaController@lista_personas')->name('lista_personas');
Route::any('formulario_persona/{id}', 'PersonaController@formulario_persona')->name('formulario_persona');
Route::any('nuevo_persona/{id}', 'PersonaController@nuevo_persona')->name('nuevo_persona');
Route::any('editar_persona/{id}', 'PersonaController@editar_persona')->name('editar_persona');
Route::get('eliminar_persona/{id}', 'PersonaController@eliminar_persona')->name('eliminar_persona');
