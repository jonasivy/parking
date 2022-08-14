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

Route::get('', fn () => response()->json([ 'status' => 'ok', ]));

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
        Route::get('{entryPoint}', [
            'as'   => 'entry-point.show',
            'uses' => 'EntryPointController@show',
        ]);
        Route::post('', [
            'as'   => 'entry-point.store',
            'uses' => 'EntryPointController@store',
        ]);
        Route::delete('{entryPoint}', [
            'as'   => 'entry-point.destroy',
            'uses' => 'EntryPointController@destroy',
        ]);
    });

Route::prefix('slot')
    ->namespace('App\Http\Controllers')
    ->group(function () {
        Route::get('', [
            'as'   => 'slot.get',
            'uses' => 'SlotController@index',
        ]);
        Route::get('{x}/{y}', [
            'as'   => 'slot.show',
            'uses' => 'SlotController@show',
        ]);
        Route::patch('{x}/{y}', [
            'as'   => 'slot.patch',
            'uses' => 'SlotController@modify',
        ]);
    });
