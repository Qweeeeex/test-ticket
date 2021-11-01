<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');

Route::get('new_ticket', 'TicketsController@create');
Route::post('new_ticket', 'TicketsController@store');
Route::get('tickets/{ticket_id}', 'TicketsController@show');
Route::get('my_tickets', 'TicketsController@userTickets');
Route::get('tickets/{ticket_id}/edit', 'TicketsController@edit');
Route::post('tickets/{ticket_id}/edit', 'TicketsController@update');
Route::post('tickets/{ticket_id}/close', 'TicketsController@close');
Route::get('tickets/{ticket_id}/downloadfile', 'TicketsController@downloadfile');
Route::post('tickets/{ticket_id}/accept', 'TicketsController@accept');


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
	Route::get('tickets', 'TicketsController@index');
    Route::get('tickets/reset', 'TicketsController@reset');
});

Route::post('comment', 'CommentsController@postComment');
