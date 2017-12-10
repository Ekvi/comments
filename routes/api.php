<?php

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

Route::get('/comments', 'Api\CommentsController@get');
Route::post('/comments/add', 'Api\CommentsController@add');
Route::put('/comments/{id}/update', 'Api\CommentsController@update');
Route::delete('/comments/{id}/delete', 'Api\CommentsController@delete');
