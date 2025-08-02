<?php

use Illuminate\Http\Request;
use App\Http\Controllers\BalanceCheckController;
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

Route::any('getbal', 'Api\ApiController@getbalance');
Route::any('getip',  'Api\ApiController@getip');

Route::any('payout', 'PayoutController@payment')->middleware(["balanceCheck"]);
Route::post('account/nsdl/kyc/submit', 'Service\OfflineAccountController@accountNsdlKyc')->name("accountNsdlKyc");
/*Aeps Bank 1*/
Route::group(['prefix' => 'aeps/bank1'], function() {
    Route::post('onboard', 'Service\Aeps1Controller@kyc');
    Route::post('transaction', 'Service\Aeps1Controller@transaction');
});

/*Aeps Bank 2*/
Route::group(['prefix' => 'aeps/bank2'], function() {
    Route::post('onboard', 'Service\Aeps2Controller@kyc');
    Route::post('transaction', 'Service\Aeps2Controller@transaction');
});

/*Mciro Atm 1*/
Route::group(['prefix' => 'matm'], function() {        
    Route::any('v1/initiate', 'Service\Aeps1Controller@microatmInitiate');
    Route::any('v1/update', 'Service\Aeps1Controller@microatmUpdate');
});

/*Recharge Api */
Route::any('recharge/providers', 'Service\RechargeController@providersList');
Route::any('recharge/request', 'Service\RechargeController@payment')->middleware(["balanceCheck"]);
Route::any('recharge/getplan', 'Service\RechargeController@getplan');

/*Billpay Api */
Route::any('billpay/providers', 'Service\BillpayController@providersList');
Route::any('billpay/getprovider', 'Service\BillpayController@getproviderData');
Route::any('billpay/fetch', 'Service\BillpayController@fetchBill');
Route::any('billpay/fetchmplan', 'Service\BillpayController@fetchBillMplan');
Route::any('billpay/transaction', 'Service\BillpayController@payment')->middleware(["balanceCheck"]);
Route::any('billpay/selfpay', 'Service\BillpayController@selfpayment')->middleware(["balanceCheck"]);

/*Money Transfer Api */
Route::any('dmt/transaction', 'Service\DmtController@payment');
Route::any('dmt/pay', 'Service\DmtController@transfer')->middleware(["balanceCheck"]);

Route::prefix('api.pan')->group(function () {
    Route::post('/apply', 'Api\PanApiController@applyForPan');
    Route::post('/status', 'Api\PanApiController@checkPanStatus');
    Route::post('/transaction-status', 'Api\PanApiController@checkTransactionStatus');
    Route::post('/reports', 'Api\PanApiController@getPanReports');
});


// NSDL API Routes
Route::prefix('api/nsdl')->group(function () {
    // Agent registration and onboarding
    Route::post('/agent/register', 'NsdlApiController@registerAgent');
    
    // Account opening
    Route::post('/account/open', 'NsdlApiController@openAccount');
    
    // Transaction reports
    Route::post('/reports', 'NsdlApiController@getReports');
    
    // Agent management
    Route::get('/agent/{id}', 'NsdlApiController@getAgentDetails');
    Route::post('/agent/update', 'NsdlApiController@updateAgent');
});
