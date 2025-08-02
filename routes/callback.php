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
Route::any('{product}/{type}', 'Service\OfflineAccountController@apply');
Route::any('paysprint', 'CallbackController@paysprintOnboard');
Route::any('nsdlpan', 'Service\PancardController@nsdlupdate')->name("nsdlupdate");

Route::group(['prefix'=> 'recharge'], function() {
    Route::any('{api}', 'CallbackController@index');
});

Route::group(['prefix'=> 'payout'], function() {
    Route::any('razor', 'CallbackController@razor');
    Route::any('payu', 'CallbackController@payu');
    Route::any('cashfree', 'CallbackController@cashfree');
});

