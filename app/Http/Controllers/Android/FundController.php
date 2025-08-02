<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Aepsfundrequest;
use App\Model\Fundreport;
use App\Model\Fundbank;
use App\Model\Paymode;
use App\Model\PortalSetting;
use App\Model\Provider;
use App\Model\Aepsreport;
use App\Model\Microatmfundrequest;
use App\Model\Microatmreport;
use App\Model\Report;
use App\Model\Api;
use Carbon\Carbon;

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
    
    public function transaction(Request $request)
    {
        \LogActivity::addToLog('App-Payout', $request);
        $rules = array(
            'apptoken' => 'required',
            'type'     => 'required',
            'user_id'  => 'required|numeric',
            'mode'     => 'sometimes|required',
        );

        $validate = \Myhelper::FormValidator($rules, $request);
        if($validate != "no"){
            return $validate;
        }
        $provide = Provider::where('recharge1', 'fund')->first();
        $user = User::where('id', $request->user_id)->first();

        if(!$user){
            $output['statuscode'] = "ERR";
            $output['message'] = "User details not matched";
            return response()->json($output);
        }

        switch ($request->type) {
            case 'transfer':
                if(!\Myhelper::can('fund_transfer', $request->user_id)){
                    return response()->json(["statuscode" => "ERR" , "message" => "Permission not allowed"]);
                }

                $request['refno'] = preg_replace('/[^A-Za-z0-9]/', '', $request->refno);
                $rules = array(
                    'amount' => 'required|numeric|min:1',
                );
        
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(['statuscode' => "ERR", "message" => $error]);
                }

                $payee = User::where('id', $request->payee_id)->first();
            
                $balance = "mainwallet";
                $table   = Report::query();
                $product = "fund transfer";

                if($balance == "mainwallet" && $user[$balance] < $request->amount){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Insufficient wallet balance."]);
                }

                $debit = [
                    'number'  => $user->mobile,
                    'mobile'  => $user->mobile,
                    'provider_id' => $request->provider_id,
                    'api_id'  => $provide->api_id,
                    'amount'  => $request->amount,
                    'txnid'   => "WTR".date('Ymdhis'),
                    'remark'  => $request->remark,
                    'refno'   => $request->refno,
                    'status'  => 'success',
                    'user_id' => $user->id,
                    'credit_by' => $payee->id,
                    'rtype'   => 'main',
                    'via'     => $request->via,
                    'balance' => $user[$balance],
                    'trans_type' => 'debit',
                    'product' => $product
                ];

                if(in_array($request->wallet, ["aepswallet", "matmwallet"])){
                    $debit['option5'] = "fund";
                    $debit['option1'] = "wallet";
                }

                $credit = [
                    'number' => $payee->mobile,
                    'mobile' => $payee->mobile,
                    'provider_id' => $request->provider_id,
                    'api_id' => $provide->api_id,
                    'amount' => $request->amount,
                    'txnid'  => "WTR".date('Ymdhis'),
                    'remark' => $request->remark,
                    'refno'  => $request->refno,
                    'status' => 'success',
                    'user_id'   => $payee->id,
                    'credit_by' => $user->id,
                    'rtype' => 'main',
                    'via'   => $request->via,
                    'balance'    => $payee[$balance],
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
                    return response()->json(['statuscode'=>"TXN", 'message' => "Fund Transfer successfully"]);
                }else{
                    return response()->json(['statuscode'=>"ERR", 'message' => "Something went wrong."]);
                }
                break;

            case 'return':
                if($request->type == "return" && !\Myhelper::can('fund_return', $request->user_id)){
                    return response()->json(["statuscode" => "ERR" , "message" => "Permission not allowed"]);
                }

                $request['refno'] = preg_replace('/[^A-Za-z0-9]/', '', $request->refno);
                $rules = array(
                    'amount' => 'required|numeric|min:1'
                );
        
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    foreach ($validator->errors()->messages() as $key => $value) {
                        $error = $value[0];
                    }
                    return response()->json(['statuscode' => "ERR", "message" => $error]);
                }

                $product = "fund return";
                $balance = "mainwallet";
                $table   = Report::query();

                $payee   = User::where('id', $request->payee_id)->first();

                if($balance == "mainwallet" && $payee[$balance] < $request->amount){
                    return response()->json(['statuscode' => "ERR", "message" => "Insufficient wallet balance"]);
                }

                $debit = [
                    'number' => $payee->mobile,
                    'mobile' => $payee->mobile,
                    'provider_id' => $request->provider_id,
                    'api_id' => $provide->api_id,
                    'amount' => $request->amount,
                    'remark' => $request->remark,
                    'refno'  => $request->refno,
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
                    'provider_id' => $request->provider_id,
                    'api_id'  => $provide->api_id,
                    'amount'  => $request->amount,
                    'remark'  => $request->remark,
                    'refno'  => $request->refno,
                    'status'  => 'success',
                    'user_id' => $user->id,
                    'credit_by' => $payee->id,
                    'rtype'   => 'main',
                    'via'     => $request->via,
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
                    return response()->json(['statuscode'=>"TXN", 'message' => "Fund Return successfully"]);
                }else{
                    return response()->json(['statuscode'=>"ERR", 'message' => "Something went wrong."]);
                }
                break;

            case 'request':
                if(!\Myhelper::can('fund_request', $request->user_id)){
                    return response()->json(['statuscode' => "ERR", "message" => "Permission not allowed"]);
                }

                $rules = array(
                    'fundbank_id' => 'required|numeric',
                    'paymode'     => 'required',
                    'amount'      => 'required|numeric|min:100',
                    'ref_no'      => 'required|unique:fundreports,ref_no',
                    'paydate'     => 'required',
                    'apptoken'    => 'required'
                );
        
                $validate = \Myhelper::FormValidator($rules, $request);
                if($validate != "no"){
                    return $validate;
                }

                $request['user_id'] = $user->id;
                $admin = User::whereHas('role', function ($q){
                    $q->where('slug', 'admin');
                })->first(['id']);
                $request['credited_by'] = $admin->id;
                
                $request['status'] = "pending";
                $action = Fundreport::create($request->all());
                if($action){
                    return response()->json(['statuscode' => "TXN", "message" => "Fund request send successfully", "txnid" => $action->id]);
                }else{
                    return response()->json(['statuscode' => "ERR", "message" => "Something went wrong, please try again."]);
                }
                break;

            case 'getfundbank':
                $data['banks'] = Fundbank::where('user_id', $user->parent_id)->where('status', '1')->get();
                if(!\Myhelper::can('setup_bank', $user->parent_id)){
                    $admin = User::whereHas('role', function ($q){
                        $q->where('slug', 'whitelable');
                    })->where('company_id', $user->company_id)->first(['id']);

                    if($admin && \Myhelper::can('setup_bank', $admin->id)){
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                    }else{
                        $admin = User::whereHas('role', function ($q){
                            $q->where('slug', 'admin');
                        })->first(['id']);
                        $data['banks'] = Fundbank::where('user_id', $admin->id)->where('status', '1')->get();
                    }
                }
                $data['paymodes'] = Paymode::where('status', '1')->get();
                return response()->json(['statuscode' => "TXN", "message" => "Get successfully", "data" => $data]);
                break;

            default :
                return response()->json(['statuscode' => "ERR", 'message' => "Bad Parameter Request"]);
            break;

            case 'transfer' :
            case 'return'   :
                if($request->type == "transfer" && !\Myhelper::can('fund_transfer', $request->user_id)){
                    return response()->json(['statuscode' => "ERR", "message" => "Permission not allowed"]);
                }

                if($request->type == "return" && !\Myhelper::can('fund_return', $request->user_id)){
                    return response()->json(['statuscode' => "ERR", "message" => "Permission not allowed"]);
                }
                
                $provide = Provider::where('recharge1', 'fund')->first();
                $request['provider_id'] = $provide->id;
        
                $rules = array(
                    'amount' => 'required|numeric|min:1',
                    'id'     => 'required' 
                );
        
                $validate = \Myhelper::FormValidator($rules, $request);
                if($validate != "no"){
                    return $validate;
                }
                
                $user  = User::where('id', $request->user_id)->first();
                $payee = User::where('id', $request->id)->first();
                
                if($request->type == "transfer"){
                    if($user->mainwallet < $request->amount){
                        return response()->json(['statuscode' => "ERR", "message" => "Insufficient wallet balance."]);
                    }
                }else{
                    if($payee->mainwallet - $payee->lockedamount < $request->amount){
                        return response()->json(['statuscode' => "ERR", "message" => "Insufficient balance in user wallet."]);
                    }
                }
                $request['txnid']   = 0;
                $request['option1'] = 0;
                $request['option2'] = 0;
                $request['option3'] = 0;
                $request['refno']   = date('ymdhis');
                return $this->paymentAction($request);
                break;
        }
    }

    public function paymentAction($request)
    {
        $user = User::where('id', $request->id)->first();

        if($request->type == "transfer" || $request->type == "request"){
            $action = User::where('id', $request->id)->increment('mainwallet', $request->amount);
        }else{
            $action = User::where('id', $request->id)->decrement('mainwallet', $request->amount);
        }

        if($action){
            if($request->type == "transfer" || $request->type == "request"){
                $request['trans_type'] = "credit";
            }else{
                $request['trans_type'] = "debit";
            }

            $insert = [
                'number' => $user->mobile,
                'mobile' => $user->mobile,
                'provider_id' => $request->provider_id,
                'api_id' => $this->fundapi->id,
                'amount' => $request->amount,
                'charge' => '0.00',
                'profit' => '0.00',
                'gst' => '0.00',
                'tds' => '0.00',
                'apitxnid' => NULL,
                'txnid' => $request->txnid,
                'payid' => NULL,
                'refno' => $request->refno,
                'description' => NULL,
                'remark' => $request->remark,
                'option1' => $request->option1,
                'option2' => $request->option2,
                'option3' => $request->option3,
                'option4' => NULL,
                'status' => 'success',
                'user_id' => $user->id,
                'credit_by' => $request->user_id,
                'rtype' => 'main',
                'via' => 'portal',
                'adminprofit' => '0.00',
                'balance' => $user->mainwallet,
                'trans_type' => $request->trans_type,
                'product' => "fund ".$request->type
            ];
            $action = Report::create($insert);
            if($action){
                return $this->paymentActionCreditor($request);
            }else{
                return response()->json(['statuscode' => "ERR", "message" => "Technical error, please contact your service provider before doing transaction."]);
            }
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Fund transfer failed, please try again."]);
        }
    }

    public function paymentActionCreditor($request)
    {
        $payee = $request->id;
        $user = User::where('id', $request->user_id)->first();
        if($request->type == "transfer" || $request->type == "request"){
            $action = User::where('id', $user->id)->decrement('mainwallet', $request->amount);
        }else{
            $action = User::where('id', $user->id)->increment('mainwallet', $request->amount);
        }

        if($action){
            if($request->type == "transfer" || $request->type == "request"){
                $request['trans_type'] = "debit";
            }else{
                $request['trans_type'] = "credit";
            }

            $insert = [
                'number' => $user->mobile,
                'mobile' => $user->mobile,
                'provider_id' => $request->provider_id,
                'api_id' => $this->fundapi->id,
                'amount' => $request->amount,
                'charge' => '0.00',
                'profit' => '0.00',
                'gst' => '0.00',
                'tds' => '0.00',
                'apitxnid' => NULL,
                'txnid' => $request->txnid,
                'payid' => NULL,
                'refno' => $request->refno,
                'description' => NULL,
                'remark' => $request->remark,
                'option1' => $request->option1,
                'option2' => $request->option2,
                'option3' => $request->option3,
                'option4' => NULL,
                'status' => 'success',
                'user_id' => $user->id,
                'credit_by' => $payee,
                'rtype' => 'main',
                'via' => 'portal',
                'adminprofit' => '0.00',
                'balance' => $user->mainwallet,
                'trans_type' => $request->trans_type,
                'product' => "fund ".$request->type
            ];

            $action = Report::create($insert);
            if($action){
                return response()->json(['statuscode' => "TXN", "message" =>  "Transaction Successfull"]);
            }else{
                return response()->json(['statuscode' => "ERR", "message" => "Technical error, please contact your service provider before doing transaction."]);
            }
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Technical error, please contact your service provider before doing transaction."]);
        }
    }
}
