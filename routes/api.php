<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('entry-point')
    ->namespace('App\Http\Controllers')
    ->group(function () {
        Route::get('', [
            'as'   => 'entry-point.index',
            'uses' => 'EntryPointController@index',
        ]);
        Route::post('', [
            'as'   => 'entry-point.store',
            'uses' => 'EntryPointController@store',
        ]);
        Route::delete('', [
            'as'   => 'entry-point.delete',
            'uses' => 'EntryPointController@delete',
        ]);
    });
