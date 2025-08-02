<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BalanceCheckController;
use App\Http\Controllers\PanUatController;
use App\Http\Controllers\Service\AccountController;


Route::get('/', 'UserController@mylogin')->middleware('guest')->name('mylogin');
Route::get('policy', 'UserController@policy')->middleware('guest')->name('policy'); 

Route::any('pan/{product}/{type}', 'Service\OfflineAccountController@apply');
Route::any('apply/{product}/{type}', 'Service\OfflineAccountController@apply');

Route::post('account/nsdl/submit', 'Service\OfflineAccountController@accountNsdlProcess')->name("accountNsdlProcess");
Route::post('account/nsdl/kyc/submit', 'Service\OfflineAccountController@accountNsdlKyc')->name("accountNsdlKyc");
Route::post('approvals/list/fetch', 'Service\OfflineAccountController@fetchApprovals')->name("pending_approvals");
Route::post('approvals/list/update', 'Service\OfflineAccountController@updateStatus')->name("updateStatus");
Route::get('approvals/list', 'Service\OfflineAccountController@pending_approvals_list')->name("pending_approvals_list");
Route::post('pan/submit', 'Service\OfflineAccountController@panApplyProcess')->name("panApplyProcess");
Route::post('pan/report', 'Service\OfflineAccountController@panreport')->name('panreportstatic');
Route::post('pan/status', 'Service\OfflineAccountController@panStatusProcess')->name('panStatusProcess');
Route::post('transaction/status', 'Service\OfflineAccountController@txnStatusProcess')->name('txnStatusProcess');
Route::post('nsdl/report', 'Service\OfflineAccountController@nsdlreport')->name('nsdlreport');

Route::group(['prefix' => 'auth'], function() {
    Route::post('check', 'UserController@login')->name('authCheck');
    Route::get('logout', 'UserController@logout')->name('logout');
    Route::post('reset', 'UserController@passwordReset')->name('authReset');
    Route::post('register', 'UserController@registration')->name('register');
    Route::post('getotp', 'UserController@getotp')->name('getotp');
    Route::post('setpin', 'UserController@setpin')->middleware(["mpin"])->name('setpin');
    Route::post('ekyc', 'UserController@ekyc')->name('ekyc');
    Route::get('routecache', 'UserController@routecache');
});

Route::get('complete/profile', 'MemberController@completeProfile')->middleware("auth")->name('memberkyc');

Route::group(['middleware' => ['auth', 'company']], function() {
    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::post('wallet/balance', 'HomeController@getbalance')->name('getbalance');
    Route::get('setpermissions', 'HomeController@setpermissions');
    Route::get('setscheme', 'HomeController@setscheme');
    Route::get('getmyip', 'HomeController@getmysendip');
    Route::get('mydata', 'HomeController@mydata');
    Route::post('mystatics', 'HomeController@statics')->name("mystatics");
    Route::post('mycommission', 'HomeController@mycommission')->name("mycommission");
    Route::post('useronboard', 'HomeController@useronboard')->name("useronboard");
    Route::post('sendotp', 'HomeController@sendotp')->middleware("service")->name('sendotp');
    Route::get('getBillerData', 'HomeController@getBillerData');
    Route::get('setBillerData', 'HomeController@setBillerData');
    Route::get('pancardClear', 'CronController@pancardClear')->name('pancardclear');
    Route::get('pancardStatus', 'CronController@pancardStatus');
    Route::get('emitraRefund', 'CronController@emitraRefund');

    Route::group(['prefix'=> 'tools', 'middleware' => ['checkrole:admin']], function() {
        Route::get('{type}', 'RoleController@index')->name('tools');
        Route::post('{type}/store', 'RoleController@store')->name('toolsstore');
        Route::post('setpermissions','RoleController@assignPermissions')->name('toolssetpermission');
        Route::post('updatepermissions','RoleController@setPermissions')->name('toolsupdatepermission');
        Route::post('get/permission/{id}', 'RoleController@getpermissions')->name('permissions');
        Route::post('getdefault/permission/{id}', 'RoleController@getdefaultpermissions')->name('defaultpermissions');
    });

    Route::group(['prefix' => 'developer/api', 'middleware' => ['auth', 'company', 'checkrole:apiuser']], function() {
        Route::get('{type}', 'ApiController@index')->name('apisetup');
        Route::post('update', 'ApiController@update')->name('apitokenstore');
        Route::post('callback', 'ApiController@callback')->name('callback');
        Route::post('token/delete', 'ApiController@tokenDelete')->name('tokenDelete');
    });

    Route::group(['prefix'=> 'statement'], function() {
        Route::get('report/{type?}/{id?}', 'Report\ReportController@index')->name('reports');
        Route::post('report/static', 'Report\ReportController@fetchData')->name('reportstatic');

        Route::get('list/{type}/{id?}/{status?}', 'Report\StatementController@index')->name('statement');
        Route::post('list/fetch/{type}/{id?}/{returntype?}', 'Report\CommonController@fetchData');
    });

    Route::group(['prefix'=> 'export'], function() {
        Route::get('report/{type}', 'Report\ExportController@export');
        Route::get("statement/{type}", 'Report\StatementController@export')->name('export');
    });

    Route::group(['prefix' => 'report/action', 'middleware' => 'service'], function() {
        Route::post('update', 'Report\CommonController@update')->name('statementUpdate');
        Route::post('status', 'Report\CommonController@status')->name('statementStatus');
        Route::post('delete', 'Report\CommonController@delete')->name('statementDelete');
    });

    Route::group(['prefix'=> 'member'], function() {
        Route::get('{type}/{action?}', 'MemberController@index')->name('member');
        Route::post('store', 'MemberController@create')->name('memberstore');
        Route::post('commission/update', 'MemberController@commissionUpdate')->name('commissionUpdate');
        Route::post('getcommission', 'MemberController@getCommission')->name('getMemberCommission');
        Route::post('getpackagecommission', 'MemberController@getPackageCommission')->name('getMemberPackageCommission');
        Route::post('getparent', 'MemberController@getparents')->name("getparent");
    });

    Route::group(['prefix'=> 'portal'], function() {
        Route::get('{type}', 'PortalController@index')->name('portal');
        Route::post('store', 'PortalController@create')->name('portalstore');
    });

    Route::group(['prefix'=> 'logs'], function() {
        Route::get('{type}', 'PortalController@logs')->name('portallogs');
    });

    Route::group(['prefix'=> 'fund'], function() {
        Route::get('{type}/{action?}', 'FundController@index')->name('fund');
        Route::post('transaction', 'FundController@transaction')->middleware(["service", "balanceCheck", "pinCheck", 'mpin'])->name('fundtransaction');
    });

    Route::group(['prefix' => 'profile', 'middleware' => ['auth']], function() {
        Route::get('download_kyc/{id}', 'SettingController@download_kyc');
        Route::get('/view/{id?}', 'SettingController@index')->name('profile');
        Route::post('update', 'SettingController@profileUpdate')->middleware("mpin")->name('profileUpdate');
    });

    Route::group(['prefix' => 'setup'], function() {
        Route::get('{type}/{id?}', 'SetupController@index')->name('setup');
        Route::post('update', 'SetupController@update')->middleware("mpin")->name('setupupdate');
    });

    Route::group(['prefix' => 'resources'], function() {
        Route::get('{type}', 'ResourceController@index')->name('resource');
        Route::post('update', 'ResourceController@update')->name('resourceupdate');
        Route::post('get/{type}/commission', 'ResourceController@getCommission');
        Route::post('get/{type}/packagecommission', 'ResourceController@getPackageCommission');
    });

    Route::group(['prefix' => 'complaint'], function() {
        Route::get('/', 'ComplaintController@index')->name('complaint');
        Route::post('store', 'ComplaintController@store')->name('complaintstore');
    });

    Route::group(['prefix'=> 'payout', 'middleware' => ['service']], function() {
        Route::post('transaction', 'PayoutController@payment')->name('payout')->middleware(["balanceCheck", "pinCheck"]);
    });

    Route::group(['prefix' => 'recharge', 'middleware' => ['service']], function() {
        Route::get('{type}', 'Service\RechargeController@index')->name('recharge');
        Route::post('payment', 'Service\RechargeController@payment')->middleware(["balanceCheck", "pinCheck"])->name('rechargepay');
        Route::post('getoperators', 'Service\RechargeController@getoperators')->name("getoperators");
        Route::post('getplan', 'Service\RechargeController@getplan')->name("plan");
        Route::post('provider/list', 'Service\RechargeController@providersList')->name('providers');
    });

    Route::group(['prefix' => 'billpay', 'middleware' => ['service']], function() {
        Route::get('{type}', 'Service\BillpayController@index')->name('bill');
        Route::post('fetch', 'Service\BillpayController@fetchBill')->name('billpayFetch');
        Route::post('payment', 'Service\BillpayController@payment')->middleware(["balanceCheck", "pinCheck"])->name('billpay');
        Route::post('provider/list', 'Service\BillpayController@providersList')->name('providersList');
        Route::post('provider/details', 'Service\BillpayController@providersDetails')->name('providersDetails');
    });

    Route::group(['prefix' => 'pancard', 'middleware' => ['service']], function() {
        Route::get('{type}', 'Service\PancardController@index')->name('pancard');
        Route::post('payment', 'Service\PancardController@payment')->middleware(["balanceCheck", "pinCheck"])->name('pancardpay');
        Route::post('uti/payment', 'Service\PancardController@utipayment')->middleware(["balanceCheck", "pinCheck"])->name('utipay');
        Route::post('create', 'Service\PancardController@pancardid')->name('pancardcreate');
        Route::post('vle/generate', 'Service\PancardController@vleid')->name('vleid');
    });

    Route::group(['prefix' => 'dmt', 'middleware' => ['service']], function() {
        Route::get('/', 'Service\DmtController@index')->name('dmt1');
        Route::post('transaction', 'Service\DmtController@payment')->name('dmt1transaction');
        Route::post('pay', 'Service\DmtController@transfer')->middleware(["balanceCheck", "pinCheck"])->name('dmt1pay');
    });

    Route::group(['prefix' => 'aeps', 'middleware' => ['service']], function() {
        Route::any('initiate', 'Service\Aeps1Controller@index')->name('aeps');
        Route::any('aadharpay', 'Service\Aeps1Controller@aadharpay')->name('aadharpay');
        Route::any('onboard', 'Service\Aeps1Controller@kyc')->name('aepskyc');
        Route::post('transaction', 'Service\Aeps1Controller@transaction')->name('aepspay');
    });

    Route::group(['prefix' => 'aeps/bank2', 'middleware' => ['service']], function() {
        Route::any('initiate', 'Service\Aeps2Controller@index')->name('aeps2');
        Route::post('onboard', 'Service\Aeps2Controller@kyc')->name('aeps2kyc');
        Route::post('transaction', 'Service\Aeps2Controller@transaction')->name('aeps2pay');
    });

    Route::group(['prefix' => 'accounts', 'middleware' => ['service']], function() {
        Route::get('{type}', 'Service\AccountController@index')->name('account');
        Route::post('transaction', 'Service\AccountController@transaction')->name('accountApply');
        Route::post('nsdl/apply', 'Service\AccountController@accountNsdlProcess')->name('nsdlaccountApply');
        Route::post('onboard', 'Service\AccountController@accountNsdlKyc')->name('nsdlaccountkyc');
        Route::post('update/agent', 'Service\OfflineAccountController@updateAgent')->name('update_agent');
        Route::get('update/agent', 'Service\OfflineAccountController@get_agent_details')->name('get_agent_details');
    });
    Route::group(['prefix' => 'service', 'middleware' => ['service']], function() {
        Route::get('/agent/registration', 'Service\BcController@index')->name('service.register');
        Route::post('/agent/registration', 'Service\BcController@register')->name('service.register.agent');
        Route::get('/agent/registration/show', 'Service\BcController@show')->name('service.register.show');
        Route::get('/agent/registration/list', 'Service\BcController@list')->name('agent.registration.list');
        Route::get('apply/nsdl/callback/saving_account', 'Service\BcController@list')->name('agent.registration.list');
    });
    Route::group(['prefix' => 'service', 'middleware' => ['service']], function() {
        Route::get('/balance-check', [BalanceCheckController::class , 'index'])->name('service.balance-check-page');
        Route::post('/fetch-balance', [BalanceCheckController::class, 'checkBalance'])->name('checkBalance');
    });
    
    // Callbacks
         


    
});
// PAN Card Service Routes
Route::get('/panuat', [PanUatController::class, 'index'])->name('pancard.service');
Route::post('/pancard/balance-check', [PanUatController::class, 'checkBalance'])->name('balancecheck');

// New PAN Request Routes
Route::post('/pancard/new-pan-request', [PanUatController::class, 'newPanRequest'])->name('pancardpay');
Route::post('/pancard/pan-request-status', [PanUatController::class, 'panStatusReq'])->name('pancardstatuscheck');
Route::post('/pancard/pan-entity-reports', [PanUatController::class, 'entityReports'])->name('checkentityReports');

// PAN Correction Routes
Route::post('/pancard/correction-request', [PanUatController::class, 'crPanReq'])->name('pancardcorrection');

// Incomplete Application Routes
Route::post('/pancard/incomplete-application', [PanUatController::class, 'incompleteApplication'])->name('incompleteapplication');

// Transaction Status Check Routes
Route::post('/pancard/check-transaction', [PanUatController::class, 'checkTransactionStatus'])->name('checktransaction');

// Legacy route for backwards compatibility
Route::post('/pancard/create', [PanUatController::class, 'newPanRequest'])->name('pancardcreate');

// Balance Check Routes

Route::get('/uat-logs', [PanUatController::class, 'show'])->name('uat_logs_page');
Route::post('/uat-logs', [PanUatController::class, 'uatLogs'])->name('uat_logs');
Route::get('/get-log-details', [PanUatController::class, 'getLogDetails'])->name('get_log_details');
Route::get('/export-logs', [PanUatController::class, 'exportLogs'])->name('export_logs');
Route::post('/delete-log', [PanUatController::class, 'deleteLog'])->name('delete_log');
Route::post('/cleanup-logs', [PanUatController::class, 'cleanupLogs'])->name('cleanup_logs');
Route::get('/search-logs', [PanUatController::class, 'searchLogs'])->name('search_logs');


Route::any('callback/nsdluat' , [AccountController::class, 'nsdlCallback']);