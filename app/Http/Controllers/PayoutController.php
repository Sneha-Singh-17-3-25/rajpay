<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Api;
use App\Model\Provider;
use App\Model\Aepsreport;
use App\Model\Report;
use App\Model\Aepsuser;
use App\Model\Commission;
use App\Model\Contact;
use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;

class PayoutController extends Controller
{
    protected $api, $admin;
    public function __construct()
    {
        $this->api   = Api::where('code', 'paysprint_payout')->first();
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();
    }

    public function payment(Request $post)
    {
        switch ($post->type) {
            case 'getaccount':
                $agent = \App\Model\Contact::where('user_id', $post->user_id)->get();
                return response()->json(['statuscode'=> 'TXN', "message" => "Account List", 'data' => $agent]);
                break;

            case 'getbanks':
                $agent = \DB::table('dmtbanks')->get();
                return response()->json(['statuscode'=> 'TXN', "message" => "Bank List", 'data' => $agent]);
                break;

            case 'getlist':
                $rules = array('user_id' => 'required');
            break;

            case 'addaccount':
                $rules = array('name' => 'required','account' => 'required','bank' => 'required','ifsc' => 'required');
            break;

            case 'uploaddocs':
                $rules = array('user_id' => 'required|numeric','passbook' => 'required','pancard' => 'required');
            break;

            case 'wallet':
                $rules = array(
                    'amount'   => 'required|numeric|min:10'
                );
            break;

            case 'bank':
                $rules = array(
                    'user_id'  => 'required|numeric',
                    'beneid'   => 'required',
                    'mode'     => 'required',
                    'amount'   => 'required|numeric|min:100'
                );
            break;
            
            case 'status':
                $rules = array('user_id' => 'required|numeric', 'id' => 'required');
            break;

            default:
                return response()->json(['statuscode'=>'ERR', 'message'=> "Invalid request format"]);
            break;
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        
        $user  = User::where("id", $post->user_id)->first();
        switch ($post->type) {
            case 'addaccount':
                $post['beneid'] = $post->user_id.date("his");
                $post['status'] = "approved";
                $action = Contact::create($post->all());
                if($action){
                    return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull', "beneid" => $post->bene_id]);
                }else{
                    return response()->json(['statuscode'=> 'TXF', 'message'=> $response->message]);
                }
                break;

            case 'getaccount':
            case 'getbanks':
            case 'getlist':
            case 'addaccount':
            case 'uploaddocs':
            case 'status':
                $payload =  [
                    "timestamp" => time(),
                    "partnerId" => $this->api->username,
                    "reqid"     => $post->user_id.Carbon::now()->timestamp
                ];

                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".JWT::encode($payload, $this->api->password),
                    "Authorisedkey: ".$this->api->optional1
                );
                
                $agent = Aepsuser::where('user_id', $post->user_id)->first();
                if(!$agent){
                    return response()->json(['statuscode'=> 'ERR', "message" => "Agent Not Found"]);
                }
                break;
        }

        switch ($post->type) {
            case 'wallet':
                $provider = Provider::where('recharge1', 'aepsfund')->first();

                if($provider->status == 0){
                    return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
                }

                if($user->aepswallet < $post->amount){
                    return response()->json(['statuscode' => "ERR" , "message" => "Low wallet balance to make this request"]);
                }

                $post['create_time'] = Carbon::now()->toDateTimeString();
                $post['txnid']       = "WTR".date('Ymdhis');
                $post['status'] = "success";

                $debit = [
                    'number'  => "Wallet",
                    'mobile'  => $user->mobile,
                    'provider_id' => $provider->id,
                    'api_id'  => $provider->api_id,
                    'amount'  => $post->amount,
                    'txnid'   => $post->txnid,
                    'payid'   => $post->txnid,
                    'refno'   => ucfirst($post->type)." Fund Recieved",
                    'description' =>  ucfirst($post->type)." Fund Recieved",
                    'remark'  => $post->remark,
                    'option1' => "wallet",
                    'option4' => $post->option4,
                    'option5' => "fund",
                    'status'  => $post->status,
                    'user_id' => $user->id,
                    'credit_by' => $user->id,
                    'rtype'   => 'main',
                    'via'     => $post->via,
                    'balance' => $user->aepswallet,
                    'trans_type' => 'debit',
                    'product'    => "payout",
                    'create_time'=> $post->create_time,
                    "option7" => $post->ip()."/".$_SERVER['HTTP_USER_AGENT'],
                    "option8" => $post->gps_location
                ];

                $credit = [
                    'number'  => "Wallet",
                    'mobile'  => $user->mobile,
                    'provider_id' => $provider->id,
                    'api_id'  => $provider->api_id,
                    'amount'  => $post->amount,
                    'txnid'   => $post->txnid,
                    'payid'   => $post->txnid,
                    'refno'   => ucfirst($post->type)." Fund Recieved",
                    'description' =>  ucfirst($post->type)." Fund Recieved",
                    'remark'  => $post->remark,
                    'option1' => $post->type,
                    'option5' => "fund",
                    'status'  => 'success',
                    'user_id' => $user->id,
                    'credit_by' => $user->id,
                    'rtype'   => 'main',
                    'via'     => $post->via,
                    'balance' => $user->mainwallet,
                    'trans_type' => 'credit',
                    "option7" => $post->ip()."/".$_SERVER['HTTP_USER_AGENT'],
                    "option8" => $post->gps_location,
                    'product'    => "payout",
                    'create_time'=> $post->create_time,
                    "option7" => $post->ip()."/".$_SERVER['HTTP_USER_AGENT'],
                    "option8" => $post->gps_location
                ];

                try {
                    $load = \DB::transaction(function () use($debit, $credit, $post, $user) {
                        User::where('id', $user->id)->decrement("aepswallet", $post->amount);
                        Aepsreport::create($debit);

                        User::where('id', $user->id)->increment("mainwallet", $post->amount);
                        Report::create($credit);
                        return true;
                    });
                } catch (\Exception $e) {
                    $load = false;
                }

                if($load){
                    return response()->json(['statuscode' => "TXN" , "message" => "Transaction Successfull", 'txnid' => $post->txnid]);
                }else{
                    return response()->json(['statuscode' => "ERR" , "message" => "Transaction Failed"]);
                }
                break;

            case 'getlist':
                $url = $this->api->url."list";
                $parameters = [
                    "merchantid" => $agent->merchantLoginId
                ];
                break;

            case 'addaccount':
                $url = $this->api->url."add";
                $parameters = [
                    "bankid"  => $post->bank,
                    "merchant_code" => $agent->merchantLoginId,
                    "account" => $post->account,
                    "ifsc" => $post->ifsc,
                    "name" => $post->name,
                    "account_type"  => "PRIMARY"
                ];
                break;

            case 'uploaddocs':
                $post['passbookdata'] = $post->file('passbook')->store('bankdata/user'.$post->user_id);
                $post['pancarddata']  = $post->file('pancard')->store('bankdata/user'.$post->user_id);
                
                $url = $this->api->url."uploaddocument";
                $parameters = [
                    "doctype"  => "PAN",
                    "bene_id"  => $post->beneid,
                    "panimage" => new \CURLFile(public_path('')."/".$post->pancarddata),
                    "passbook" => new \CURLFile(public_path('')."/".$post->passbookdata)
                ];
                
                $header = array(
                    "Token: ".JWT::encode($payload, $this->api->password),
                    "Authorisedkey: ".$this->api->optional1
                );
        
                break;
                
            case 'bank':
                $contact = \DB::table('contacts')->where('id', $post->beneid)->first();
                if(!$contact){
                    return response()->json(['statuscode' => "ERR", "message" => "Invalid Contact"]);
                }

                $bank = \DB::table('dmtbanks')->where('bankid', $contact->bank)->first();
                $post['account'] = $contact->account;
                $post['ifsc'] = $contact->ifsc;
                $post['bank'] = $contact->bank;

                if($post->mode == "IMPS"){
                    if($post->amount > 0 && $post->amount <= 25000){
                        $provider = Provider::where('recharge1', 'payout1')->first();
                    }elseif($post->amount > 25000 && $post->amount <= 200000){
                        $provider = Provider::where('recharge1', 'payout2')->first();
                    }
                }elseif($post->mode == "NEFT"){
                    $provider = Provider::where('recharge1', 'payoutneft')->first();
                }

                $post['charge'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $provider->id, $user->role->slug);
                if($user->aepswallet < $post->amount + $post->charge){
                    return response()->json(['statuscode' => "ERR", "message" => "Insufficient Wallet Fund"]);
                }

                $previousrecharge = Aepsreport::where('number', $post->account)->where('amount', $post->amount)->where('user_id', $post->user_id)->whereBetween('created_at', [Carbon::now()->subMinutes(1)->format('Y-m-d H:i:s'), Carbon::now()->addMinutes(1)->format('Y-m-d H:i:s')])->count();
                if($previousrecharge > 0){
                    return response()->json(['statuscode' => "ERR", "message" => "Transaction Allowed After 2 Min."]);
                }

                do {
                    $post['txnid'] = $this->transcode().'PO'.rand(111111111111, 999999999999);
                } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);
                
                $debit = [
                    'number'  => $post->account,
                    'mobile'  => $user->mobile,
                    'provider_id' => $provider->id,
                    'api_id'  => $provider->api_id,
                    'amount'  => $post->amount,
                    'charge'  => $post->charge,
                    'txnid'   => $post->txnid,
                    'apitxnid'=> $post->apitxnid,
                    'option1' => "bank",
                    'option2' => $post->ifsc,
                    'option3' => $post->bank,
                    'option4' => $post->callback,
                    'option5' => "fund",
                    'status'  => "accept",
                    'user_id' => $user->id,
                    'credit_by' => $user->id,
                    'rtype'   => 'main',
                    'via'     => $post->via,
                    'balance' => $user->aepswallet,
                    'trans_type' => 'debit',
                    'product'    => "payout",
                    'create_time'=> Carbon::now()->toDateTimeString()
                ];

                try {
                    $report = \DB::transaction(function () use($debit, $post, $user) {
                        $report = Aepsreport::create($debit);
                        User::where('id', $user->id)->decrement("aepswallet", $post->amount + $post->charge);
                        return $report;
                    });
                } catch (\Exception $e) {
                    $report = false;
                    \DB::table('log_500')->insert([
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'log'  => $e->getMessage(),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                if(!$report){
                    return response()->json(['statuscode'=>"ERR", 'message' => "Something went wrong."]);
                }

                switch ($provider->api->code) {
                    case "payout1":
                        $url = $this->api->url."dotransaction";
                        $parameters = [
                            "bene_id"  => $contact->beneid,
                            "amount"   => $post->amount,
                            "refid"    => $post->txnid,
                            "mode"     => $post->mode
                        ];
                        break;

                    default:
                        return response()->json([
                            'statuscode' => 'TXN', 
                            'message'    => 'Transaction Successfull', 
                            'refno'      => $post->txnid, 
                            'txnid'      => $post->txnid
                        ]);
                        break;
                }
                break;
                
            case 'status':
                $report = Aepsfundrequest::where('id', $post->id)->whereIn('status', ['approved', 'pending'])->first();
                
                if(!$report){
                    return response()->json(['statuscode' => "ERR", "message" => "Status Not Allowed"]);
                }
                
                $url = $this->api->url."status";
                $parameters = [
                    "refid" => $report->txnid,
                    "ackno" => $report->payid
                ];
                break;

            default:
                return response()->json(['statuscode'=> 'ERR', 'message'=> 'Bad Parameter Request']);
                break;
        }   

        if($post->type != "payout" && $post->type != "uploaddocs"){
            $result = \Myhelper::curl($url, "POST", json_encode($parameters), $header, "no");
        }elseif($post->type == "uploaddocs"){
            $result = \Myhelper::curl($url, "POST", $parameters, $header, "no");
        }else{
            $result = \Myhelper::curl($url, "POST", json_encode($parameters), $header, "yes", 'Payout', $post->txnid);
        }
        
        \DB::table('rp_log')->insert([
            'ServiceName' => "Payout-".$post->type,
            'header'      => json_encode($header),
            'body'        => json_encode($parameters),
            'response'    => $result['response'],
            'url'         => $url,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
        
        if($result['response'] == ""){
            if($post->type == "payout"){
                return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull']);
            }else{
                return response()->json(['statuscode'=> 'ERR', 'message'=> "Something went wrong"]);
            }
        }
        
        $response = json_decode($result['response']);
        switch ($post->type) {
            case 'getlist':
                dd($response);
                break;

            case 'addaccount':
                if(isset($response->response_code) && $response->response_code == "1"){
                    $post['beneid'] = $response->bene_id;
                    $post['status'] = "approved";
                    Contact::create($post->all());
                    return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull', "beneid" => $response->bene_id]);
                }elseif(isset($response->response_code) && in_array($response->response_code, ["2"])){
                    $post['beneid'] = $response->bene_id;
                    $post['status'] = "document";
                    Contact::create($post->all());

                    if($post->has('passbook')){
                        $post['type'] = "uploaddocs";
                        return $this->payment($post);
                    }
                    return response()->json(['statuscode'=> 'TXNDOC', 'message'=> 'Transaction Successfull', "beneid" => $response->bene_id]);
                }else{
                    return response()->json(['statuscode'=> 'TXF', 'message'=> $response->message]);
                }
                break;

            case 'uploaddocs':
                if(isset($response->response_code) && $response->response_code == "1"){
                    Contact::where('beneid', $post->beneid)->update(['status' => "approved"]);
                    return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull']);
                }else{
                    return response()->json(['statuscode'=> 'TXF', 'message'=> $response->message]);
                }
                break;

            case 'bank':
                switch ($provider->api->code) {
                    case 'payout1':
                        if(isset($response->response_code) && $response->response_code == "1"){
                            Aepsreport::where('id', $report->id)->update(['status' => "success", 'refno' => $response->ackno, 'payid' => $response->ackno]);
                            return response()->json([
                                'statuscode'=> 'TXN', 
                                'message'=> 'Transaction Successfull', 
                                'refno' => $response->ackno
                            ]);
                        }elseif(isset($response->response_code) && $response->response_code != "1"){
                            Aepsreport::where('id', $report->id)->update(['status' => "failed", 'refno' => $response->message]);
                            User::where('id', $post->user_id)->increment('aepswallet', $post->amount + $post->charge);
                            return response()->json(['statuscode'=> 'TXF', 'message'=> $response->message]);
                        }else{
                            Aepsreport::where('id', $report->id)->update(['status' => "pending"]);
                            return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull', 'refno' => $post->txnid]);
                        }
                        break;
                }
                break;

            case 'status':
                if(isset($response->response_code) && $response->response_code == "1"){
                    if($response->data->txn_status == "1"){
                        return response()->json(['statuscode'=> 'TXN', 'refno'=> $response->data->utr, 'remark' => $response->data->status]);
                    }else{
                        return response()->json(['status'=> 'success', 'refno'=> isset($response->data->utr)?$response->data->utr : 'pending', 'remark' => $response->message]);
                    }
                }else{
                    return response()->json(['statuscode'=> 'ERR', 'message'=> isset($response->message) ? $response->message : 'Status Not Fetched']);
                }
                break;
        }
    }
}
 