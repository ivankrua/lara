<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceListController;

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
Route::group(['middleware' => ['cors', 'json.response']], function () {
    // public routes
    Route::post('/login', 'Api\AuthController@login')->name('login.api');
    Route::post('/register','Api\AuthController@register')->name('register.api');
    Route::post('/logout', 'Api\AuthController@logout')->name('logout.api');
    Route::get('/pricelist', 'PriceListController@index')->name('pricelist.get');
});
Route::middleware('auth:api')->group(function () {
    Route::apiResource('/admin_pricelist', 'PriceListAdminController');
    Route::post('/servers', 'ImportController@store')->name('pricelist.import');
    Route::get('/logout', 'Api\AuthController@logout')->name('logout');
});
