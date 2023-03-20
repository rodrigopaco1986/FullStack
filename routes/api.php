<?php

use App\Http\Controllers\BattleController;
use App\Http\Controllers\MonsterController;
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

Route::controller(MonsterController::class)->prefix('monsters')->group(function () {
    Route::get('', 'index');
    Route::get('{id}', 'show');
    Route::post('', 'store');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'remove');
    Route::post('import-csv', 'importCsv');
});

Route::controller(BattleController::class)->prefix('battles')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::delete('{id}', 'remove');
});
