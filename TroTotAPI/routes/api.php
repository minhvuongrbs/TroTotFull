<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route:: group([
  'prefix' => 'v1',
  'namespace' => 'Api',
],function(){
  //user
  Route::post('/register','ControllerAuth@register');
  Route::post('/login','ControllerAuth@login');

  Route::get('/post-room','ControllerPostRoom@index');
  // Route::post('/post-room','ControllerPostRoom@store');
  Route::get('/post-room/{id}','ControllerPostRoom@show');

});
