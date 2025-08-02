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
/*Android Auth Apis*/
Route::any('auth/slide', 'Android\UserController@slide');
Route::any('auth/v1', 'Android\UserController@login');
Route::any('auth/logout', 'Android\UserController@logout');
Route::any('auth/register', 'UserController@registration');
Route::any('auth/reset', 'UserController@passwordReset');
Route::any('completekyc', 'Android\UserController@completeKyc');
Route::any('sendotp', 'HomeController@sendotp')->name('sendotp');

// Member Apis
Route::any('getbalance', 'Android\UserController@getbalance');
Route::any('changePassword', 'Android\UserController@changePassword');
Route::any('getcommission', 'Android\UserController@getcommission');
Route::any('getbalance', 'Android\UserController@getbalance');
Route::any('tpin/getotp', 'UserController@getotp');
Route::any('tpin/generate', 'UserController@setpin');

// Transaction Report
Route::any('transaction', 'Report\ReportController@fetchData');
Route::any('transaction/status', 'Android\TransactionController@transactionStatus');

// Fund Api
Route::any('fundrequest', 'Android\FundController@transaction');
Route::any('aepsfund', 'PayoutController@payment')->middleware(["balanceCheck", "pinCheck"]);

/*Aeps Bank 1*/
Route::group(['prefix' => 'aeps/bank1'], function() {
    Route::any('onboard', 'Service\Aeps1Controller@kyc');
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
Route::any('recharge/pay', 'Service\RechargeController@payment')->middleware(["balanceCheck", "pinCheck"]);
Route::any('recharge/getplan', 'Service\RechargeController@getplan');

/*Billpay Api */
Route::any('billpay/providers',   'Service\BillpayController@providersList');
Route::any('billpay/getprovider', 'Service\BillpayController@getproviderData');
Route::any('billpay/fetch',       'Service\BillpayController@fetchBill');
Route::any('billpay/fetchmplan',  'Service\BillpayController@fetchBillMplan');
Route::any('billpay/transaction', 'Service\BillpayController@payment')->middleware(["balanceCheck", "pinCheck"]);
Route::any('billpay/selfpay',     'Service\BillpayController@selfpayment')->middleware(["balanceCheck", "pinCheck"]);

/*Money Transfer Api */
Route::any('dmt/transaction', 'Service\DmtController@payment');
Route::any('dmt/pay', 'Service\DmtController@transfer')->middleware(["balanceCheck", "pinCheck"]);

