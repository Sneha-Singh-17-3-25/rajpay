<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Fundreport;
use App\Model\Aepsfundrequest;
use App\Model\Report;
use App\Model\Fundbank;
use App\Model\Paymode;
use App\Model\Api;
use App\Model\Provider;
use App\Model\Aepsreport;
use App\Model\PortalSetting;
use App\Model\Microatmfundrequest;
use App\Model\Microatmreport;
use App\Model\Order;
use App\Model\Qrcode;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Firebase\JWT\JWT;

class FundController extends Controller
{
    public $fundapi, $admin;

    public function __construct()
    {
        $this->fundapi = Api::where('code', 'fund')->first();
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();
    }

    public function index($type, $action="none")
    {
        $data = [];
        switch ($type) {
            case 'tr':
                $permission = ['fund_transfer', 'fund_return'];
                break;
            
            case 'request':
            case 'upirequest':
                $permission = 'fund_request';
                break;
            
            case 'requestview':
                $permission = 'fund_requestview';
                break;
            
            case 'statement':
            case 'requestviewall':
                $permission = 'fund_report';
                break;

            case 'aeps':
                $data['banks'] = \DB::table('dmtbanks')->get();
                $permission = 'aeps_fund_request';
                break;
            
            case 'aepsrequest':
            case 'payout':
                $permission = 'aeps_fund_view';
                break;

            case 'aepsfund':
            case 'aepsrequestall':
            case 'aepsapproved':
                $permission = 'aeps_fund_report';
                break;

            case 'payoutbank':
                $permission = 'payout_bank';
                break;

            default:
                abort(404);
                break;
        }

        if (isset($permission) && !\Myhelper::can($permission)) {
            abort(403);
        }

        if (isset($role) && !\Myhelper::hasRole($role)) {
            abort(403);
        }

        if ($this->fundapi->status == "0") {
            abort(503);
        }

        switch ($type) {
            case 'request':
                $admin = User::whereHas('role', function ($q){
                    $q->where('slug', 'admin');
                })->first(['id']);
                $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                $data['paymodes'] = Paymode::where('status', '1')->get();
                break;

            case 'upirequest':
                $data['qrcode'] = Qrcode::where('user_id', \Auth::id())->first();
                break;
        }

        return view('fund.'.$type)->with($data);
    }

    public function transaction(Request $post)
    {
        if ($this->fundapi->status == "0") {
            return response()->json(['status' => "This function is down."],400);
        }
        $provide = Provider::where('recharge1', 'fund')->first();
        $post['provider_id'] = $provide->id;

        switch ($post->type) {
            case 'transfer':
                if(!\Myhelper::can('fund_transfer')){
                    return response()->json(["status" => "ERR" , "message" => "Permission not allowed"]);
                }

                $post['refno'] = preg_replace('/[^A-Za-z0-9]/', '', $post->refno);
                $rules = array(
                    'amount' => 'required|numeric|min:1',
                    'refno'  => 'required|unique:reports,refno',
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(['status' => "ERR", "message" => $error]);
                }

                $payee  = \Auth::user();
                $user   = User::where('id', $post->payee_id)->first();

                if(\Myhelper::hasRole("admin")){
                    $balance = $post->wallet;

                    if($post->wallet == "aepswallet"){
                        $table = Aepsreport::query();
                    }else{
                        $table = Report::query();
                    }
                }else{
                    $balance = "mainwallet";
                    $table = Report::query();
                }
                $product = "fund transfer";

                if($balance == "mainwallet" && $payee[$balance] < $post->amount){
                    return response()->json(['status'=>"ERR", 'message' => "Insufficient wallet balance."]);
                }

                $debit = [
                    'number'  => $payee->mobile,
                    'mobile'  => $payee->mobile,
                    'provider_id' => $post->provider_id,
                    'api_id'  => $provide->api_id,
                    'amount'  => $post->amount,
                    'txnid'   => "WTR".date('Ymdhis'),
                    'remark'  => $post->remark,
                    'refno'   => $post->refno,
                    'status'  => 'success',
                    'user_id' => $payee->id,
                    'credit_by' => $user->id,
                    'rtype'   => 'main',
                    'via'     => $post->via,
                    'balance' => $payee[$balance],
                    'trans_type' => 'debit',
                    'product' => $product
                ];

                if(in_array($post->wallet, ["aepswallet", "matmwallet"])){
                    $debit['option5'] = "fund";
                    $debit['option1'] = "wallet";
                }

                $credit = [
                    'number' => $user->mobile,
                    'mobile' => $user->mobile,
                    'provider_id' => $post->provider_id,
                    'api_id' => $provide->api_id,
                    'amount' => $post->amount,
                    'txnid'  => "WTR".date('Ymdhis'),
                    'remark' => $post->remark,
                    'refno'  => $post->refno,
                    'status' => 'success',
                    'user_id'   => $user->id,
                    'credit_by' => $payee->id,
                    'rtype' => 'main',
                    'via'   => $post->via,
                    'balance'    => $user[$balance],
                    'trans_type' => 'credit',
                    'product'    => $product
                ];

                $request = \DB::transaction(function () use($debit, $credit, $balance, $table) {
                    if($balance == "mainwallet"){
                        $debitReport = $table->create($debit);
                        User::where('id', $debit['user_id'])->decrement($balance, $debit['amount']);
                    }

                    $creditReport = $table->create($credit);
                    User::where('id', $credit['user_id'])->increment($balance, $credit['amount']);
                    return true;
                });

                if($request){
                    return response()->json(['status'=>"TXN", 'message' => "Fund Transfer successfully"]);
                }else{
                    return response()->json(['status'=>"ERR", 'message' => "Something went wrong."]);
                }
                break;

            case 'return':
                if($post->type == "return" && !\Myhelper::can('fund_return')){
                    return response()->json(["status" => "ERR" , "message" => "Permission not allowed"]);
                }

                $post['refno'] = preg_replace('/[^A-Za-z0-9]/', '', $post->refno);
                $rules = array(
                    'amount' => 'required|numeric|min:1'
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(['status' => "ERR", "message" => $error]);
                }
                $product = "fund return";
                if(\Myhelper::hasRole("admin")){
                    $balance = $post->wallet;

                    if($post->wallet == "aepswallet"){
                        $table = Aepsreport::query();
                    }else{
                        $table = Report::query();
                    }
                }else{
                    $balance = "mainwallet";
                }

                $user    = \Auth::user();
                $payee   = User::where('id', $post->payee_id)->first();

                if($balance == "mainwallet" && $payee[$balance] < $post->amount){
                    return response()->json(['status' => "ERR", "message" => "Insufficient wallet balance"]);
                }

                $debit = [
                    'number' => $payee->mobile,
                    'mobile' => $payee->mobile,
                    'provider_id' => $post->provider_id,
                    'api_id' => $provide->api_id,
                    'amount' => $post->amount,
                    'remark' => $post->remark,
                    'refno'  => $post->refno,
                    'status' => 'success',
                    'user_id'=> $payee->id,
                    'credit_by' => $user->id,
                    'rtype'   => 'main',
                    'via'     => 'portal',
                    'balance' => $payee[$balance],
                    'trans_type'  => 'debit',
                    'product' => $product
                ];

                $credit = [
                    'number'  => $user->mobile,
                    'mobile'  => $user->mobile,
                    'provider_id' => $post->provider_id,
                    'api_id'  => $provide->api_id,
                    'amount'  => $post->amount,
                    'remark'  => $post->remark,
                    'refno'  => $post->refno,
                    'status'  => 'success',
                    'user_id' => $user->id,
                    'credit_by' => $payee->id,
                    'rtype'   => 'main',
                    'via'     => $post->via,
                    'balance' => $user->mainwallet,
                    'trans_type' => 'credit',
                    'product' => $product,
                ];

                $request = \DB::transaction(function () use($debit, $credit, $balance, $table) {
                    $debitReport = $table->create($debit);
                    User::where('id', $debit['user_id'])->decrement($balance, $debit['amount']);

                    if($balance == "mainwallet"){
                        $creditReport = $table->create($credit);
                        User::where('id', $credit['user_id'])->increment($balance, $credit['amount']);
                    }
                    return true;
                });

                if($request){
                    return response()->json(['status'=>"TXN", 'message' => "Fund Return successfully"]);
                }else{
                    return response()->json(['status'=>"ERR", 'message' => "Something went wrong."]);
                }
                break;

            case 'requestview':
                if(!\Myhelper::can('setup_bank')){
                    return response()->json(['status'=>"ERR", 'message' => "Permission not allowed"]);
                }

                $fundreport = Fundreport::where('id', $post->id)->where('status', 'pending')->first();
                if(!$fundreport){
                    return response()->json(['status'=>"ERR", 'message' => "Already Updated"]);
                }
                
                $post['amount'] = $fundreport->amount;

                if ($post->status == "approved") {
                    if(\Auth::user()->mainwallet < $post->amount){
                        return response()->json(['status'=>"ERR", 'message' => "Insufficient wallet balance."]);
                    }

                    $action = Fundreport::where('id', $post->id)->update([
                        "status" => $post->status,
                        "remark" => $post->remark
                    ]);

                    $payee  = \Auth::user();
                    $user   = User::where('id', $fundreport->user_id)->first();

                    $debit = [
                        'number'  => $payee->mobile,
                        'mobile'  => $payee->mobile,
                        'provider_id' => $post->provider_id,
                        'api_id'  => $provide->api_id,
                        'amount'  => $post->amount,
                        'txnid'   => $fundreport->id,
                        'refno'   => $fundreport->ref_no,
                        'remark'  => $post->remark,
                        'option1' => $fundreport->fundbank_id,
                        'option2' => $fundreport->paymode,
                        'option3' => $fundreport->paydate,
                        'status'  => 'success',
                        'user_id' => $payee->id,
                        'credit_by' => $user->id,
                        'rtype'   => 'main',
                        'via'     => 'portal',
                        'balance' => $payee->mainwallet,
                        'trans_type'  => 'debit',
                        'product' => "fund request"
                    ];

                    $credit = [
                        'number' => $user->mobile,
                        'mobile' => $user->mobile,
                        'provider_id' => $post->provider_id,
                        'api_id' => $provide->api_id,
                        'amount' => $post->amount,
                        'txnid'   => $fundreport->id,
                        'refno'   => $fundreport->ref_no,
                        'remark'  => $post->remark,
                        'option1' => $fundreport->fundbank_id,
                        'option2' => $fundreport->paymode,
                        'option3' => $fundreport->paydate,
                        'status' => 'success',
                        'user_id'   => $user->id,
                        'credit_by' => $payee->id,
                        'rtype' => 'main',
                        'via'   => $post->via,
                        'balance'    => $user->mainwallet,
                        'trans_type' => 'credit',
                        'product' => "fund request"
                    ];

                    $request = \DB::transaction(function () use($debit, $credit) {
                        $debitReport = Report::create($debit);
                        User::where('id', $debit['user_id'])->decrement("mainwallet", $debit['amount']);
                        
                        $creditReport = Report::create($credit);
                        User::where('id', $credit['user_id'])->increment("mainwallet", $credit['amount']);
                        return true;
                    });

                    if($request){
                        return response()->json(['status'=>"TXN", 'message' => "Transaction successfully"]);
                    }else{
                        return response()->json(['status'=>"ERR", 'message' => "Something went wrong."]);
                    }
                }else{
                    $action = Fundreport::where('id', $post->id)->update([
                        "status" => $post->status,
                        "remark" => $post->remark
                    ]);

                    if($action){
                        return response()->json(['status'=>"TXN", 'message' => "Transaction successfully"]);
                    }else{
                        return response()->json(['status'=>"ERR", 'message' => "Something went wrong, please try again."]);
                    }
                }
                break;

            case 'request':
                if(!\Myhelper::can('fund_request')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }

                $rules = array(
                    'fundbank_id' => 'required|numeric',
                    'paymode'     => 'required',
                    'amount'      => 'required|numeric|min:100',
                    'ref_no'      => 'required|unique:fundreports,ref_no',
                    'paydate'     => 'required'
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $post['user_id'] = \Auth::id();
                $admin = User::whereHas('role', function ($q){
                    $q->where('slug', 'admin');
                })->first(['id']);
                $post['credited_by'] = $admin->id;
                
                $post['status'] = "pending";
                if($post->hasFile('payslips')){
                    $post['payslip'] = $post->file('payslips')->store('deposit_slip');
                }
                $action = Fundreport::create($post->all());
                if($action){
                    return response()->json(['status' => "success"],200);
                }else{
                    return response()->json(['status' => "Something went wrong, please try again."],200);
                }
                break;

            case 'loadwallet':
                if(\Myhelper::hasNotRole('admin')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }
                
                $action = User::where('id', \Auth::id())->increment('mainwallet', $post->amount);
                if($action){
                    $insert = [
                        'number' => \Auth::user()->mobile,
                        'mobile' => \Auth::user()->mobile,
                        'provider_id' => $post->provider_id,
                        'api_id' => $this->fundapi->id,
                        'amount' => $post->amount,
                        'charge' => '0.00',
                        'profit' => '0.00',
                        'gst' => '0.00',
                        'tds' => '0.00',
                        'apitxnid' => NULL,
                        'txnid' => date('ymdhis'),
                        'payid' => NULL,
                        'refno' => NULL,
                        'description' => NULL,
                        'remark' => $post->remark,
                        'option1' => NULL,
                        'option2' => NULL,
                        'option3' => NULL,
                        'option4' => NULL,
                        'status' => 'success',
                        'user_id' => \Auth::id(),
                        'credit_by' => \Auth::id(),
                        'rtype' => 'main',
                        'via' => 'portal',
                        'adminprofit' => '0.00',
                        'balance' => \Auth::user()->mainwallet,
                        'trans_type' => 'credit',
                        'product' => "fund ".$post->type
                    ];
                    $action = Report::create($insert);
                    if($action){
                        return response()->json(['status' => "success"], 200);
                    }else{
                        return response()->json(['status' => "Technical error, please contact your service provider before doing transaction."],400);
                    }
                }else{
                    return response()->json(['status' => "Fund transfer failed, please try again."],400);
                }
                break;
            
            case 'qrcode':
                if(!\Myhelper::can('fund_request')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }
                $rules = array(
                    'acc_no'   => 'required',
                    'ifsccode' => 'required',
                    'mobile'   => 'required',
                    'address'  => 'required',
                    'city'     => 'required',
                    'state'    => 'required',
                    'pincode'  => 'required',
                    'merchant_name' => 'required',
                    'pan'      => 'required'
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                do {
                    $post['refid'] = $this->transcode().'UPI'.rand(111111111111, 999999999999);
                } while (Qrcode::where("refid", "=", $post->refid)->first() instanceof Qrcode);

                $post['user_id'] = \Auth::id();
                $post['vpa']     = "ABRY".$post->mobile."@icici";

                $qrapi = Api::where('code', 'paysprint_upi')->first();
                $payload =  [
                    "timestamp" => time(),
                    "partnerId" => $qrapi->username,
                    "reqid"     => $post->user_id.Carbon::now()->timestamp
                ];

                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".JWT::encode($payload, $qrapi->password),
                    "Authorisedkey: ".$qrapi->optional1
                );

                $result = \Myhelper::curl($qrapi->url."createvpa", "POST", json_encode($post->except(["user_id", "_token", "gps_location", "lat", "lon", "gps_location", "via"])), $header, "yes", 'Vpa', $post->refid);
                \DB::table('rp_log')->insert([
                    'ServiceName' => "Create-VPA",
                    'header'      => json_encode($header),
                    'body'        => json_encode($post->except(["user_id", "_token", "gps_location", "lat", "lon", "gps_location", "via"])),
                    'response'    => $result['response'],
                    'url'         => $qrapi->url."createvpa",
                    'created_at'  => date('Y-m-d H:i:s')
                ]);

                if($result['response'] == ""){
                    return response()->json(['statuscode'=> 'ERR', 'message'=> "Something went wrong"]);
                }

                $response = json_decode($result['response']);
                
                if(isset($response->response_code) && $response->response_code == "1"){
                    $post['merchant_code'] = $response->merchant_code;
                    $post['status'] = "success";
                    $qrcode = Qrcode::create($post->all());

                    $payload =  [
                        "timestamp" => time(),
                        "partnerId" => $qrapi->username,
                        "reqid"     => $post->user_id.Carbon::now()->timestamp
                    ];

                    $header = array(
                        "Cache-Control: no-cache",
                        "Content-Type: application/json",
                        "Token: ".JWT::encode($payload, $qrapi->password),
                        "Authorisedkey: ".$qrapi->optional1
                    );

                    $parameter = [
                        "merchant_code" => $response->merchant_code
                    ];

                    $result = \Myhelper::curl($qrapi->url."static_qr", "POST", json_encode($parameter), $header, "yes", 'Vpa', $post->refid);

                    \DB::table('rp_log')->insert([
                        'ServiceName' => "Get Qr Link",
                        'header'      => json_encode($header),
                        'body'        => json_encode($parameter),
                        'response'    => $result['response'],
                        'url'         => $qrapi->url."static_qr",
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);

                    if($result['response'] != ""){
                        $response = json_decode($result['response']);

                        if(isset($response->response_code) && $response->response_code == "1"){
                            $update['qr_lLink'] = $response->qr_lLink;
                            $update['refid']  = $response->refid;
                            $update['status'] = "complete";
                            Qrcode::where("id", $qrcode->id)->update($update);
                        }else{

                            $update['qr_lLink'] = $response->message;
                            $update['status']   = "failed";
                            Qrcode::where("id", $qrcode->id)->update($update);
                        }
                    }
                    return response()->json(['statuscode'=> 'TXN', 'message'=> $response->message]);
                }else{
                    return response()->json(['statuscode'=> 'TXF', 'message'=> $response->message]);
                }
                break;
            
            case 'qrcode_dynamic':
                if(!\Myhelper::can('fund_request')){
                    return response()->json(['status' => "Permission not allowed"],400);
                }
                $rules = array(
                    'amount'   => 'required|numeric|min:100'
                );
        
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $qrapi  = Api::where('code', 'paysprint_upi')->first();
                $qrcode = Qrcode::where("user_id", $post->user_id)->first();

                $payload =  [
                    "timestamp" => time(),
                    "partnerId" => $qrapi->username,
                    "reqid"     => $post->user_id.Carbon::now()->timestamp
                ];

                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".JWT::encode($payload, $qrapi->password),
                    "Authorisedkey: ".$qrapi->optional1
                );

                $parameter = [
                    "merchant_code" => $qrcode->merchant_code,
                    "amount" => $post->amount
                ];

                $result = \Myhelper::curl($qrapi->url."dynamic_qr", "POST", json_encode($parameter), $header, "yes", 'Vpa', $post->refid);

                \DB::table('rp_log')->insert([
                    'ServiceName' => "Dynamic-Qr",
                    'header'      => json_encode($header),
                    'body'        => json_encode($parameter),
                    'response'    => $result['response'],
                    'url'         => $qrapi->url."dynamic_qr",
                    'created_at'  => date('Y-m-d H:i:s')
                ]);

                if($result['response'] == ""){
                    return response()->json(['statuscode'=> 'ERR', 'message'=> "Something went wrong"]);
                }

                $response = json_decode($result['response']);
                
                if(isset($response->response_code) && $response->response_code == "1"){
                    return response()->json(['statuscode'=> 'TXN', 'message'=> $response->message, "qr_link" => $response->qr_link]);
                }else{
                    return response()->json(['statuscode'=> 'TXF', 'message'=> $response->message]);
                }
                break;

            default:
                # code...
                break;
        }
    }
}
