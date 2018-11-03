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
  //post
  Route::get('/post-room','ControllerPostRoom@index');
  Route::post('/post-room','ControllerPostRoom@store');
  Route::get('/post-room/{id}','ControllerPostRoom@show');
  Route::get('map','ControllerPostRoom@show_map');
  Route::post('search','ControllerPostRoom@result');
  //galerys
  Route::post('media/upload_file','MediaController@uploadfile');

  //comments
  Route::post('comment','CommentController@store');
  Route::get('comment/{id}','CommentController@show');

  //notifications
  Route::get('notifications/{id}','NotificationController@show');

});
