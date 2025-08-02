<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Report;
use App\Model\Provider;
use App\Model\NpsAccount;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AccountController extends Controller
{
    public function index($type)
    {
        $data =[];
        switch ($type) {
            case 'nps':
            case 'nsdl':
                $data['bankagent'] = true;
$data['user'] = DB::table('users')->where('id', \Auth::id())->first();

$data['username'] = isset($data['user']->name) ? explode(" ", $data['user']->name) : [];
$data['firstname'] = $data['username'][0] ?? '';
$data['lastname'] = $data['username'][1] ?? '';

$data['state'] = DB::table('circles')->orderBy('state', 'asc')->get();
                return view("service.account.".$type)->with($data);
                break;
                
            default:
                abort(404);
                break;
        }
    }

    public function transaction(Request $post)
    {
        $rules = [
            'name'     => 'required',
            'email'    => 'required',
            'mobile'   => 'required',
            'district' => 'required'
        ];

        if($post->via == "api"){
            $rules["apitxnid"] = "required|unique:reports,apitxnid";
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        $action = NpsAccount::create($post->all());
        if ($action) {
            return response()->json(['statuscode' => "TXN", "message" => "Transaction successfully", "url" => "https://icicinps.finnate.app/aWNpY2lwcnVwZW5zaW9uLzIvcmVmZXJyYWxDb2RlP3JlZmVycmFsQ29kZT1JQ0lDSU5QU0ZZMjRGVDM="]);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
        }
    }

public function accountNsdlKyc(Request $post)
{
    // Check if a pending agent exists for the user
    $bankagent = DB::table('emitra_agents')
        ->where('user_id', $post->user_id)
        ->where("status", "pending")
        ->first();

    // Validation rules
    $rules = [
        'agentName'        => 'required',
        'agentLastName'    => 'required',
        'agentDob'         => 'required',
        'agentEmail'       => 'required|email',
        'agentShopName'    => 'required',
        'agentCityName'    => 'required',
        'agentPhoneNumber' => 'required|numeric|digits:10|unique:bankagents,agentPhoneNumber',
        'userPan'          => 'required|unique:bankagents,userPan',
        'agentPinCode'     => 'nullable|numeric|digits:6',
        'lat'              => 'required',
        'lon'              => 'required'
    ];

    // Validate request
    $validate = \Myhelper::FormValidator($rules, $post);
    if ($validate !== "no") {
        return $validate;
    }

    // Insert new agent record
    $insert = [
        'agentName'        => $post->agentName,
        'agentLastName'    => $post->agentLastName,
        'agentDob'         => $post->agentDob,
        'agentEmail'       => $post->agentEmail,
        'agentPhoneNumber' => $post->agentPhoneNumber,
        'agentAddress'     => $post->agentAddress ?? null,
        'agentCityName'    => $post->agentCityName,
        'agentShopName'    => $post->agentShopName,
        'agentState'       => $post->agentState ?? null,
        'agentPinCode'     => $post->agentPinCode ?? null,
        'userPan'          => $post->userPan,
        'status'           => 'pending',
        'user_id'          => $post->user_id,
        'lat'              => $post->lat,
        'lon'              => $post->lon,
        'created_at'       => now(),
        'updated_at'       => now(),
    ];

    $response = DB::table('emitra_agents')->insert($insert);

    if ($response) {
        return response([
            'statuscode' => "TXN",
            "message"    => "Agent Nsdl account onboarding process completed. Please wait for approval"
        ]);
    } else {
        return response([
            'statuscode' => "ERR",
            "message"    => "Something went wrong"
        ]);
    }
}




    public function accountNsdlProcess(Request $post)
    {
        $rules = array(
            'Customername' => 'required',
            'Email'    => 'required',
            'mobileNo' => 'required',
            'panNo'    => 'required'
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        
        $post["name"]   = $post->Customername;
        $post["email"]  = $post->Email;
        $post["mobile"] = $post->mobileNo;
        $post["user_id"] = 0;
        $post["type"] = "nsdl";

        $action = NpsAccount::create($post->all());
        if ($action) {
            $checksumStringCheck = "pin|nomineeName|nomineeDob|relationship|add2|add1|nomineeState|nomineeCity|add3|dateofbirth|pincode|customerLastName|mobileNo|customername|email|partnerRefNumber|clientid|dpid|customerDematId|bcid|applicationdocketnumber|tradingaccountnumber|bcagentid|customerRefNumber|partnerpan|partnerid|channelid|partnerCallBackURL|income|middleNameOfMother|panNo|kycFlag|maritalStatus|houseOfFatherOrSpouse";
            $name = explode(" ", $post->name);

            $jsonbody = '{
                "nomineeName": "",
                "nomineeDob": "",
                "relationship": "",
                "add1": "",
                "add2": "",
                "add3": "",
                "pin": "",
                "nomineeState": "",
                "nomineeCity": "",
                "customername": "",
                "customerLastName": "",
                "dateofbirth": "",
                "pincode": "",
                "email": "'.$post->email.'",
                "mobileNo": "'.$post->mobileNo.'",
                "maritalStatus": "",
                "income": "",
                "middleNameOfMother": "",
                "houseOfFatherOrSpouse": "",
                "kycFlag": "",
                "panNo": "'.$post->panNo.'",
                "channelid": "HDRMhwNrRvXdEeKqcjfu",
                "partnerid": "tccw8O4HEq",
                "applicationdocketnumber": "",
                "dpid": "",
                "clientid": "",
                "partnerpan": "'.$post->panNo.'",
                "tradingaccountnumber": "",
                "partnerRefNumber": "",
                "customerRefNumber": "",
                "customerDematId": "",
                "partnerCallBackURL": "https://www.google.com/?q=sampleRedirect&oq=sampleRedirect",
                "bcid": "1443",
                "bcagentid": "UTTAM123"
            }';

            $body = '{"nomineeDetails":{"nomineeName": "","nomineeDob": "","relationship": "","add1": "","add2": "","add3": "","pin": "","nomineeState": "","nomineeCity": ""},"personalDetails":{"customername": "","customerLastName": "","dateofbirth": "","pincode": "","email": "'.$post->email.'","mobileNo": "'.$post->mobileNo.'"},"otherDeatils":{"maritalStatus": "","income": "","middleNameOfMother": "","houseOfFatherOrSpouse": "","kycFlag": "","panNo": "'.$post->panNo.'"},"additionalParameters":{"channelid": "HDRMhwNrRvXdEeKqcjfu","partnerid": "tccw8O4HEq","applicationdocketnumber": "","dpid": "","clientid": "","partnerpan": "BSIPT9237P","tradingaccountnumber": "","partnerRefNumber": "","customerRefNumber": "","customerDematId": "","partnerCallBackURL": "https://www.google.com/?q=sampleRedirect&oq=sampleRedirect","bcid": "1443","bcagentid": "UTTAM123"}}';

            $arraybody = json_decode($jsonbody, true);
            $checksumStringCheckArray = explode("|", $checksumStringCheck);
            $checksumString = "";
            foreach ($checksumStringCheckArray as $key => $value) {
                $checksumString .= $arraybody[$value];
            }

            $fkey="vAouxIEOwuSqpjmYhkcJXmGy3oqmdOrQdArkVmn0MJxvzjNdh5ouJlw3Mf8Kz8mTcDyNahZ4BAT6mkw5P7BV8hlRg6gm13ESTnrh22kMXCp7LpKCzHsxcpXiDylGuzrN";
            $signcs = base64_encode(hash_hmac('sha512', $checksumString, $fkey, true));

            $key= "vAouxIEOwuSqpjmY";
            $iv = "vAouxIEOwuSqpjmY";
            $encrypted = openssl_encrypt($body, 'aes-128-cbc', $key, 0, $iv);

            return response()->json(["statuscode" => "TXN", "message" => "https://jiffyuat.nsdlbank.co.in/jarvisjiffytest/accountOpen?partnerid=tccw8O4HEq&signcs=".$signcs."&encryptedStringCustomer=".urlencode($encrypted)]);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
        }
    }
    
    
    
    public function nsdlCallback(Request $request)
    {
        DB::table('apilogs')->insert([
            'url'=>'',
            'txnid'=>'txn'.rand(100000 , 999999),
            'header'=>'',
            'request'=>'',
            'response'=>$request
            
            ]);
    }
}
