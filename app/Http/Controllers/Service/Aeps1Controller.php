<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use App\Model\Fingagent;
use App\Model\Aepsreport;
use App\Model\Commission;
use App\Model\Provider;
use App\Model\Api;

class Aeps1Controller extends Controller
{
    protected $api;

    public function __construct()
    {
        $this->api = Api::where('code', 'fing_aeps')->first();
    }

    public function index(Request $post, $t="0")
    {
        if (\Myhelper::hasRole('admin') || !\Myhelper::can('aeps1_service')) {
            abort(403);
        }
    
        $data['tab']   = $t;
        $data['agent'] = Fingagent::where('user_id', \Auth::id())->first();
        $data['state'] = \DB::table('circles')->orderBy('state','asc')->get();
        $data['aepsbanks'] = \DB::table('aepsbanks')->orderBy('bankName', 'asc')->get();
        return view('service.fing_aeps')->with($data);
    }

    public function aadharpay(Request $post, $t="0")
    {
        if (\Myhelper::hasRole('admin') || !\Myhelper::can('aeps1_service')) {
            abort(403);
        }
    
        $data['tab'] = $t;
        $data['agent'] = Fingagent::where('user_id', \Auth::id())->first();
        $data['state'] = \DB::table('circles')->orderBy('state','asc')->get();
        $data['aepsbanks'] = \DB::table('aepsbanks')->orderBy('bankName', 'asc')->get();
        return view('service.fing_aadharpay')->with($data);
    }

    public function kyc(Request $post)
    {
        $user = User::where('id', $post->user_id)->first(); 
        $post['superMerchantId'] = $this->api->optional1;
        
        if (!\Myhelper::can('aeps1_service', $post->user_id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
        }

        switch ($post->transactionType) {
            case 'getdata':
                $data['agent'] = Fingagent::where('user_id', $post->user_id)->first();
                $data['state'] = \DB::table('circles')->orderBy('state','asc')->get();
                $data['aepsbanks'] = \DB::table('aepsbanks')->get();
                return response()->json(['statuscode' => "TXN", "message" => "Data Fetched Successfully", "data" => $data]);
                break;
                
            case 'useronboard':
                $agent = Fingagent::where('user_id', $post->user_id)->where("status", "rejected")->first();
                if(!$agent){
                    $rules = array(
                        'merchantName'     => 'required',
                        'merchantAddress'  => 'required',
                        'merchantState'    => 'required',
                        'merchantCityName' => 'required',
                        'merchantPhoneNumber' => 'required|numeric|digits:10|unique:fingagents,merchantPhoneNumber',
                        'merchantAadhar'   => 'required|numeric|digits:12|unique:fingagents,merchantAadhar',
                        'userPan' => 'required|unique:fingagents,userPan',
                        'merchantPinCode'  => 'numeric|digits:6',
                        'lat' => 'required',
                        'lon' => 'required'
                    );
                }else{
                    $rules = array(
                        'merchantName'     => 'required',
                        'merchantAddress'  => 'required',
                        'merchantState'    => 'required',
                        'merchantCityName' => 'required',
                        'merchantPhoneNumber' => 'required|numeric|digits:10',
                        'merchantAadhar'   => 'required|numeric|digits:12',
                        'userPan'    => 'required',
                        'merchantPinCode'  => 'numeric|digits:6',
                        'lat' => 'required',
                        'lon' => 'required'
                    );
                }
                break;
                
            case 'useronboardotp':
                $rules = array(
                    'transactionType' => 'required',
                    'merchantAadhar'  => 'required',
                    'userPan' => 'required',
                    'lat' => 'required',
                    'lon' => 'required'
                );
                break;
                
            case 'useronboardvalidate':
                $rules = array(
                    'transactionType'   => 'required',
                    'primaryKeyId'      => 'required',
                    'encodeFPTxnId'     => 'required',
                    'otp'    => 'required',
                );
                break;
                
            case 'useronboardekyc':
                $rules = array(
                    'transactionType' => 'required',
                    'primaryKeyId'    => 'required',
                    'encodeFPTxnId'   => 'required',
                    'biodata'         => 'required'
                );
                break;
                
            case 'authentication':
                $rules = array(
                    'transactionType' => 'required',
                    'serviceType'     => 'required',
                    'biodata'         => 'required'
                );
                break;

            default:
                return response()->json(['statuscode' => "ERR", "message" => "Invalid Transaction Type1"]);
                break;
        }

        $sessionkey = '';
        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        foreach ($mt_rand as $chr)
        {             
            $sessionkey .= chr($chr);         
        }

        $iv =  '06f2f04cc530364f';
        $fp = fopen("cert/fingpay_public_production.txt","r");
        $publickey =fread($fp,8192);         
        fclose($fp);         
        openssl_public_encrypt($sessionkey,$crypttext,$publickey);

        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }

            return response()->json(['statuscode' => 'ERR', 'message'=> $error]);
        }
        
        switch ($post->transactionType) {
            case 'useronboard':     
                do {
                    $post['merchantLoginId']  = $user->agentcode;
                    $post['merchantLoginPin'] = $user->agentcode;
                } while (Fingagent::where("merchantLoginId", "=", $post->merchantLoginId)->first() instanceof Fingagent);

                $json =  [
                    "username" => $this->api->username,
                    "password" => $this->api->password,
                    "latitude"       => $post->lat,
                    "longitude"      => $post->lon,
                    "supermerchantId"=> $post->superMerchantId,
                    "merchants"      => [[
                        "merchantLoginId"     => $post->merchantLoginId, 
                        "merchantLoginPin"    => $post->merchantLoginPin,
                        "merchantName"        => $post->merchantName,
                        "merchantPhoneNumber" => $post->merchantPhoneNumber,
                        "merchantPinCode"     => $post->merchantPinCode,
                        "merchantCityName"    => $post->merchantCityName,
                        "merchantAddress"=> [
                            "merchantAddress" => $post->merchantAddress,
                            "merchantState"   => $post->merchantState
                        ],
                        "kyc"=> [
                            "userPan" => $post->userPan                        
                        ]
                    ]]
                ];
                
                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext)         
                ];

                $url = $this->api->url.'fpaepsweb/api/onboarding/merchant/creation/php/m1';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result = \Myhelper::curl($url, 'POST', $request, $header, "yes", "aepsreport", $post->merchantLoginId);

                if($result['response'] == ''){
                    return response()->json(['statuscode' => 'TUP', 'message'=>'User onboard pending']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->data->merchants[0]->status) && in_array($response->data->merchants[0]->status, ["Successfully Created,Please do EKYC to do success Transactions", "Successfully Updated"])){
                        try {
                            \Storage::deleteDirectory('kyc/useronboard'.$post->user_id);
                        } catch (\Exception $e) {}

                        if($post->hasFile('aadharPics')){
                            $post['aadharPic'] = $post->file('aadharPics')->store('kyc/useronboard'.$post->user_id);
                        }
                        if($post->hasFile('pancardPics')){
                            $post['pancardPic'] = $post->file('pancardPics')->store('kyc/useronboard'.$post->user_id);
                        }
                        $post['status'] = "approved";
                        $agent = Fingagent::create($post->all());
                        if($agent){
                            return response()->json([
                                'statuscode' => 'TXN', 
                                'message'=>'User onboard successfully',
                                'merchantLoginId'  => $post->merchantLoginId,
                                'merchantLoginPin' => $post->merchantLoginPin
                            ]);
                        }else{
                            return response()->json(['statuscode' => 'ERR', 'message'=>'Something went wrong']);
                        }
                    }else{
                        return response()->json(['statuscode' => 'ERR', 'message'=> isset($response->data->merchants[0]->remarks) ? $response->data->merchants[0]->remarks : 'Something went wrong']);
                    }
                }
                break;
            
            case 'useronboardotp':
                $agent = Fingagent::where("user_id", $post->user_id)->first();
                if(!$agent){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                if($agent->everify != "pending"){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Merchant Already Verified']);
                }

                $json =  [
                    "latitude"        => $post->lat,
                    "longitude"       => $post->lon,
                    "superMerchantId" => $post->superMerchantId,
                    "merchantLoginId" => $agent->merchantLoginId, 
                    "aadharNumber"    => $post->merchantAadhar,
                    "panNumber"       => $post->userPan,
                    "mobileNumber"    => $agent->merchantPhoneNumber,
                    "matmSerialNumber"=> "",
                    "transactionType" => 'EKY'
                ];

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext),
                    'deviceIMEI:352801082418919'
                ];

                $url     = 'https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/sendotp';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result  = \Myhelper::curl($url, 'POST', $request, $header, "no", "aepsreport", $agent->merchantLoginId);
                if($result['response'] == ''){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Something went wrong, try again']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        return response()->json(['statuscode' => 'TXN', 'message' => 'Otp Sent Successfully', "primaryKeyId" => $response->data->primaryKeyId, "encodeFPTxnId" => $response->data->encodeFPTxnId]);
                    }else{
                        if(isset($response->message) && $response->message == "Merchant Already Verified"){
                            Fingagent::where('user_id', $post->user_id)->update(['everify' => 'success']);
                            return response()->json(['statuscode' => 'ERR', 'message'=> 'E-kyc Successfully Completed']);
                        }
                        
                        if(isset($response->message) && $response->message == "Ekyc already Verified"){
                            Fingagent::where('user_id', $post->user_id)->update(['everify' => 'success']);
                            return response()->json(['statuscode' => 'ERR', 'message'=> 'E-kyc Successfully Completed']);
                        }
                    
                        return response()->json(['statuscode' => 'ERR', 'message' => isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                }
                break;
                
             case 'useronboardvalidate':
                $agent = Fingagent::where("user_id", $post->user_id)->first();
                if(!$agent){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                if($agent->everify != "pending"){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Merchant Already Verified']);
                }

                $json =  [
                    "superMerchantId" => $post->superMerchantId,
                    "merchantLoginId" => $agent->merchantLoginId, 
                    "primaryKeyId"    => $post->primaryKeyId,
                    "encodeFPTxnId"   => $post->encodeFPTxnId,
                    "otp"   => $post->otp,
                ];

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext),
                    'deviceIMEI:352801082418919'
                ];

                $url     = 'https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/validateotp';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result  = \Myhelper::curl($url, 'POST', $request, $header, "no", "aepsreport", $agent->merchantLoginId);

                if($result['response'] == ''){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Something went wrong, try again']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        return response()->json(['statuscode' => 'TXN', 'message'=> 'Otp Sent Successfully']);
                    }else{
                        return response()->json(['statuscode' => 'ERR', 'message'=> isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                }
                break;
                
            case 'useronboardekyc':
                $agent = Fingagent::where("user_id", $post->user_id)->first();
                if(!$agent){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                if($agent->everify != "pending"){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Merchant Already Verified']);
                }

                try {
                    $biodata       =  str_replace("&lt;","<",str_replace("&gt;",">",$post->biodata));
                    $xml           =  simplexml_load_string($biodata);
                    $skeyci        =  (string)$xml->Skey['ci'][0];
                    $headerarray   =  json_decode(json_encode((array)$xml), TRUE);
                } catch (\Exception $e) {
                    return response()->json(['statuscode' => "ERR", "message" => $e->getMessage()]);
                }
                
                try {
                    $json =  [
                        "captureResponse" => [
                            "PidDatatype" =>  "X",
                            "Piddata"     =>  $headerarray['Data'],
                            "ci"          =>  $skeyci,
                            "dc"          =>  $headerarray['DeviceInfo']['@attributes']['dc'],
                            "dpID"        =>  $headerarray['DeviceInfo']['@attributes']['dpId'],
                            "errCode"     =>  $headerarray['Resp']['@attributes']['errCode'],
                            "errInfo"     =>  isset($headerarray['Resp']['@attributes']['errInfo'])?$headerarray['Resp']['@attributes']['errInfo']:'',
                            "fCount"      =>  $headerarray['Resp']['@attributes']['fCount'],
                            "fType"       =>  $headerarray['Resp']['@attributes']['fType'],
                            "hmac"        =>  $headerarray['Hmac'],
                            "iCount"      =>  "0",
                            "iType"       =>  null,
                            "mc"          =>  $headerarray['DeviceInfo']['@attributes']['mc'],
                            "mi"          =>  $headerarray['DeviceInfo']['@attributes']['mi'],
                            "nmPoints"    =>  isset($headerarray['Resp']['@attributes']['nmPoints'])?$headerarray['Resp']['@attributes']['nmPoints']:'',
                            "pCount"      =>  "0",
                            "pType"       =>  "0",
                            "qScore"      =>  isset($headerarray['Resp']['@attributes']['qScore'])?$headerarray['Resp']['@attributes']['qScore']:'',
                            "rdsID"       =>  $headerarray['DeviceInfo']['@attributes']['rdsId'],
                            "rdsVer"      =>  $headerarray['DeviceInfo']['@attributes']['rdsVer'],
                            "sessionKey"  =>  $headerarray['Skey']
                        ],
    
                        "cardnumberORUID"       => [
                            'adhaarNumber'      => $agent->merchantAadhar,
                            "indicatorforUID"   => "0",
                            "nationalBankIdentificationNumber" => null
                        ],
                        "superMerchantId" => $post->superMerchantId,
                        "merchantLoginId" => $agent->merchantLoginId, 
                        "primaryKeyId"    => $post->primaryKeyId,
                        "encodeFPTxnId"   => $post->encodeFPTxnId,
                        "requestRemarks"  => "kyc"
                    ];
                } catch (\Exception $e) {
                    \DB::table('log_500')->insert([
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'log'  => $e->getMessage(). "/".$post->biodata."/".$post->via,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    return response()->json(['statuscode' => "ERR", "message" => $e->getMessage()]);
                }

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext),
                    'deviceIMEI:352801082418919'
                ];

                $url     = 'https://fpekyc.tapits.in/fpekyc/api/ekyc/merchant/php/biometric';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result  = \Myhelper::curl($url, 'POST', $request, $header, "no", "aepsreport", $agent->merchantLoginId);
                
                if($result['response'] == ''){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Something went wrong, try again']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        Fingagent::where('user_id', $post->user_id)->update(['everify' => 'success']);
                        return response()->json(['statuscode' => 'TXN', 'message'=> 'E-kyc Successfully Completed']);
                    }else{
                        return response()->json(['statuscode' => 'ERR', 'message'=> isset($response->message) ? $response->message : 'Something went wrong']);
                    }
                }
                break;
                
            case 'authentication':
                if($user->aepsbalance < -50){
                    return response()->json(['statuscode' => "ERR" , "message" => "Low wallet balance to make this request"]);
                }

                $agent = Fingagent::where("user_id", $post->user_id)->first();
                if(!$agent){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Invalid Merchant']);
                }

                try {
                    $biodata       =  str_replace("&lt;","<",str_replace("&gt;",">",$post->biodata));
                    $xml           =  simplexml_load_string($biodata);
                    $skeyci        =  (string)$xml->Skey['ci'][0];
                    $headerarray   =  json_decode(json_encode((array)$xml), TRUE);
                } catch (\Exception $e) {
                    return response()->json(['statuscode' => "ERR", "message" => $e->getMessage()]);
                }
                
                try {
                    $json =  [
                        "captureResponse" => [
                            "PidDatatype" =>  "X",
                            "Piddata"     =>  $headerarray['Data'],
                            "ci"          =>  $skeyci,
                            "dc"          =>  $headerarray['DeviceInfo']['@attributes']['dc'],
                            "dpID"        =>  $headerarray['DeviceInfo']['@attributes']['dpId'],
                            "errCode"     =>  $headerarray['Resp']['@attributes']['errCode'],
                            "errInfo"     =>  isset($headerarray['Resp']['@attributes']['errInfo'])?$headerarray['Resp']['@attributes']['errInfo']:'',
                            "fCount"      =>  $headerarray['Resp']['@attributes']['fCount'],
                            "fType"       =>  $headerarray['Resp']['@attributes']['fType'],
                            "hmac"        =>  $headerarray['Hmac'],
                            "iCount"      =>  "0",
                            "mc"          =>  $headerarray['DeviceInfo']['@attributes']['mc'],
                            "mi"          =>  $headerarray['DeviceInfo']['@attributes']['mi'],
                            "nmPoints"    =>  isset($headerarray['Resp']['@attributes']['nmPoints']) ? $headerarray['Resp']['@attributes']['nmPoints'] : "0",
                            "pCount"      =>  "0",
                            "pType"       =>  "0",
                            "qScore"      =>  isset($headerarray['Resp']['@attributes']['qScore'])?$headerarray['Resp']['@attributes']['qScore']:"0",
                            "rdsID"       =>  $headerarray['DeviceInfo']['@attributes']['rdsId'],
                            "rdsVer"      =>  $headerarray['DeviceInfo']['@attributes']['rdsVer'],
                            "sessionKey"  =>  $headerarray['Skey']
                        ],
                        "cardnumberORUID"       => [
                            'adhaarNumber'      => $agent->merchantAadhar,
                            "indicatorforUID"   => "0",
                            "nationalBankIdentificationNumber" => null
                        ],
                        "superMerchantId" => $post->superMerchantId,
                        "merchantTransactionId"     => date("ymdhis").$user->id,
                        "serviceType"     => $post->serviceType,
                        "languageCode"    => "en",
                        "latitude"        => $post->lat,
                        "longitude"       => $post->lon,
                        "mobileNumber"    => $agent->merchantPhoneNumber,
                        "requestRemarks"  => "Aeps", 
                        "transactionType"  => "AUO",
                        "merchantUserName" => $agent->merchantLoginId,
                        "merchantPin"      => md5($agent->merchantLoginPin)
                    ];
                } catch (\Exception $e) {
                    \DB::table('log_500')->insert([
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'log'  => $e->getMessage(). "/".$post->biodata."/".$post->via,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    return response()->json(['statuscode' => "ERR", "message" => $e->getMessage()]);
                }

                try {
                    if(isset($headerarray['DeviceInfo']['additional_info'])){
                        if(isset($headerarray['DeviceInfo']['additional_info']['Param']['@attributes']['value'])){
                            $deviceIMEI = $headerarray['DeviceInfo']['additional_info']['Param']['@attributes']['value'];
                        }else{
                            $deviceIMEI = $headerarray['DeviceInfo']['additional_info']['Param'][0]['@attributes']['value'];
                        }
                    }else{
                        if(isset($headerarray['additional_info']['Param']['@attributes']['value'])){
                            $deviceIMEI = $headerarray['additional_info']['Param'][0]['@attributes']['value'];
                        }else{
                            $deviceIMEI = $headerarray['additional_info']['Param'][0]['@attributes']['value'];
                        }
                    }
                } catch (\Exception $e) {
                    \DB::table('log_500')->insert([
                        'line' => "Device Error",
                        'file' => "finger",
                        'log'  => $e->getMessage(). "/".$post->biodata."/".$post->via,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    return response()->json(['statuscode' => "ERR", "message" => $e->getMessage()]);
                }

                $post["deviceIMEI"] = $deviceIMEI;

                $header = [         
                    'Content-Type: text/xml',             
                    'trnTimestamp:'.date('d/m/Y H:i:s'),         
                    'hash:'.base64_encode(hash("sha256",json_encode($json), True)),   
                    'eskey:'.base64_encode($crypttext),
                    'deviceIMEI:'.$deviceIMEI
                ];

                $url     = 'https://fingpayap.tapits.in/fpaepsservice/auth/tfauth/merchant/php/validate/aadhar';
                $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
                $request = base64_encode($ciphertext_raw);
                $result  = \Myhelper::curl($url, 'POST', $request, $header, "no", "aepsreport", $agent->merchantLoginId);
                
                try {
                    $json["captureResponse"] = "";
                    \App\Model\Apilog::create([
                        "url"      => $url,
                        "modal"    => "Aeps",
                        "txnid"    => $agent->merchantLoginId,
                        "header"   => $header,
                        "request"  => json_encode($json),
                        "response" => $result['response']
                    ]);
                } catch (\Exception $e) {
                }

                if($result['response'] == ''){
                    return response()->json(['statuscode' => 'ERR', 'message'=>'Something went wrong, try again']);
                }else{
                    $response = json_decode($result['response']);
                    if(isset($response->status) && $response->status == "true"){
                        if($post->serviceType == "AEPS"){
                            if($agent->auth == "need"){
                                Fingagent::where('user_id', $post->user_id)->update(['auth' => "yes"]);
                            }else{
                                Fingagent::where('user_id', $post->user_id)->update(['auth' => "yes", 'auth_cw' => time()]);
                            }
                        }else{
                            if($agent->aadhar_auth == "need"){
                                Fingagent::where('user_id', $post->user_id)->update(['aadhar_auth' => "yes"]);
                            }else{
                                Fingagent::where('user_id', $post->user_id)->update(['aadhar_auth' => "yes", 'aadhar_auth_cw' => time()]);
                            }
                        }

                        if($post->serviceType == "AEPS" && $agent->auth == "need"){
                            $this->twoFaCharge($post, $agent, $response, "Aeps");
                        }

                        if($post->serviceType == "AP" && $agent->aadhar_auth == "need"){
                            $this->twoFaCharge($post, $agent, $response, "AadharPay");
                        }

                        return response()->json(['statuscode' => 'TXN', 'message'=> '2FA Boametric Authentication Successfully Completed']);
                    }else{
                        if(isset($response->data->responseCode) && in_array($response->data->responseCode, ['KK','KP','KS','KU','KV','UA','UB','UC','UD','UF','UG','UH','UJ','UK','UL','UM','UN','UO','UP','UQ','UR','UY','UZ','U0','U1','U2','U3','U4','U5','U6','U7','U8','U9','VA','VB','VC','VD','VE','VF','VG','VH','VI','VJ','VK','VL','VT','VU','VV','VW','VX','VY','VZ','V0','V1','V2','V3','V4','V5','V6','V7','V8','V9','W0','W1','W2','W3','W4','W5','W6','W7','W8','WB','WC','WD','WE','WF','WG','WH','WI','WJ','WK','WL','WM','WN','WO','WP','WU','WV','WW','WX','WY','X1','X2','XA','A1','XC','XD','XE','XF','XG','XH','XI','XJ','XK','XL','Y1','Z1','F0','F1','F2','F3','F4','F5','O1','O2','O3','OI','OJ','OK','MV','OL','OX','Z2','X4','X3','KP','K3','K4','K5','K6','K7','KO','K9','KA','KB','KR','KC','KD','E1','KE','KF','KG','KH','KI','KJ','KN','X0','X5','X6','OQ','F6', '317','531','318','332','591','700','710','720','730','740','800','810','721','820','821','901','902','910','911','912','913','511','940','941','100','200','300','500','510','520','530','540','550','571','572','573','574','575','576','577','578','579','580','581','582','501','502','503','542','543','541','311','561','562','563','564','565','566','567','568','569','570','312','313','314','315','504','812','310','584','585','316','402','512','551','524','555','556','527','553','554','586','587','588','590','822','557','558','552','559','560','505','521','513','403','516','518','519','532','592','593','914','915','916','917','528','514','320','333','334','430','K-516','K-519','110','111','112','113','114','115','522','952','953','K-514','K-520','K-521','K-531','K-540','K-541','K-542','K-543','K-544','K-547','K-550','K-551','K-552','K-553','K-569','K-570','K-571','K-600','K-601','K-602','K-603','K-604','K-605','K-956','401','583','993','523','K-546','3501-E','3502-E','3503-E','3504-E','3517-E','3592-E','4005-E','4006-E','3714-E','AH000','US','FP071','FP015','0102'])){

                            if($post->serviceType == "AEPS" && $agent->auth == "need"){
                                $this->twoFaCharge($post, $agent, $response, "Aeps");
                            }

                            if($post->serviceType == "AP" && $agent->aadhar_auth == "need"){
                                $this->twoFaCharge($post, $agent, $response, "AadharPay");
                            }
                        }

                        if(isset($response->data->responseMessage)){
                            return response()->json(['statuscode' => 'ERR', 'message'=> $response->data->responseMessage]);
                        }else{
                            return response()->json(['statuscode' => 'ERR', 'message'=> isset($response->message) ?$response->message : 'Something went wrong']);
                        }
                    }
                }
                break;
        }
    }

    public function twoFaCharge($post, $agent, $response, $product)
    {
        try {
            $provider = Provider::where('recharge1', '2FACHARGE')->first();
            $insert = [
                "mobile"  => $agent->merchantPhoneNumber,
                "number"  => $agent->merchantLoginId,
                "txnid"   => isset($response->data->fingpayTransactionId) ? $response->data->fingpayTransactionId : "2FACHARGE".$user->id.date("ymdhis"),
                "refno"   => isset($response->data->bankRrn) ? $response->data->bankRrn : "",
                "payid"   => isset($response->data->responseCode) ? $response->data->responseCode : "",
                "remark"  => isset($response->data->responseMessage) ? $response->data->responseMessage : "",
                "amount"  => ".95",
                "user_id" => $post->user_id,
                'status'  => 'success',
                'credit_by'  => $post->user_id,
                'trans_type' => 'debit',
                'balance' => $this->getAccBalance($post->user_id, "aepsbalance"),
                'provider_id' => $provider->id,
                'api_id'  => $provider->api_id,
                'product' => '2fa',
                'via'     => $post->via,
                'option1' => $product,
                'option2' => $post->deviceIMEI,
                'option5' => "transaction",
                "option7" => $post->ip()."/".$_SERVER['HTTP_USER_AGENT'],
                "option8" => $post->gps_location
            ];

            $report = \DB::transaction(function () use($insert, $post) {
                User::where('id', $insert['user_id'])->decrement('aepsbalance', $insert["amount"]);
                $report = Aepsreport::create($insert);
                return $report;
            });
        } catch (\Exception $e) {
            \DB::table('log_500')->insert([
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'log'  => $e->getMessage(),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function transaction(Request $post)
    {
        if($this->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Service Currently Down."]);
        }
        $post['superMerchantId'] = $this->api->optional1;
        
        $user = User::where('id', $post->user_id)->first(); 
        if (!\Myhelper::can('aeps1_service', $post->user_id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
        }

        switch ($post->transactionType) {
            case 'BE':
            case 'MS':
                $rules = array(
                    'transactionType' => 'required',
                    'mobileNumber'    => 'required|numeric|digits:10',
                    'adhaarNumber'    => 'required|numeric|digits:12',
                    'iinno'           => 'required',
                    'biodata'         => 'required',
                    'lat' => 'required',
                    'lon' => 'required'
                );
                break;

            case 'CW':
            case 'M':
                $rules = array(
                    'transactionType' => 'required',
                    'mobileNumber'    => 'required|numeric|digits:10',
                    'adhaarNumber'    => 'required|numeric|digits:12',
                    'iinno'           => 'required',
                    'biodata'         => 'required',
                    'transactionAmount' => 'required|numeric|min:1|max:10000',
                    'lat' => 'required',
                    'lon' => 'required'
                );
                break;
            
            default:
                return response()->json(['statuscode' => "ERR", "message" => "Invalid Transaction Type"]);
                break;
        }

        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(['statuscode' => 'ERR', 'message'=> $error]);
        }
        
        $agent = Fingagent::where('user_id', $post->user_id)->first();
        if(!$agent){
            return response()->json(['statuscode' => "ERR", "message" => "User Not Onboarded"]);
        }

        if($agent->status != "approved" ){
            return response()->json(['statuscode' => "ERR", "message" => "User Onboard ".ucfirst($agent->status)]);
        }

        if($agent->auth == "need"){
            return response()->json(['statuscode' => "TXF", "status" => "auth_failed", "message" => "Agent 2FA verification is required"]);
        }

        $sessionkey = '';
        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        foreach ($mt_rand as $chr)
        {             
            $sessionkey .= chr($chr);         
        }

        $iv =  '06f2f04cc530364f';
        $fp = fopen("cert/fingpay_public_production.txt","r");
        $publickey =fread($fp,8192);         
        fclose($fp);         
        openssl_public_encrypt($sessionkey,$crypttext,$publickey);

        try {
            $biodata       =  str_replace("&lt;","<",str_replace("&gt;",">", $post->biodata));
            $xml           =  simplexml_load_string($biodata);
            $skeyci        =  (string)$xml->Skey['ci'][0];
            $headerarray   =  json_decode(json_encode((array)$xml), TRUE);
        } catch (\Exception $e) {
            \DB::table('log_500')->insert([
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'log'  => $e->getMessage(). "/".$post->biodata,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return response()->json(['status' => "ERR", "message" => $e->getMessage()]);
        }

        do {
            $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
        } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Report);
        $bank = \DB::table('aepsbanks')->where('iinno', $post->iinno)->first();

        $json =  [
            "captureResponse" => [
                "PidDatatype" =>  "X",
                "Piddata"     =>  $headerarray['Data'],
                "ci"          =>  $skeyci,
                "dc"          =>  $headerarray['DeviceInfo']['@attributes']['dc'],
                "dpID"        =>  $headerarray['DeviceInfo']['@attributes']['dpId'],
                "errCode"     =>  $headerarray['Resp']['@attributes']['errCode'],
                "errInfo"     =>  isset($headerarray['Resp']['@attributes']['errInfo'])?$headerarray['Resp']['@attributes']['errInfo']:'',
                "fCount"      =>  $headerarray['Resp']['@attributes']['fCount'],
                "fType"       =>  $headerarray['Resp']['@attributes']['fType'],
                "hmac"        =>  $headerarray['Hmac'],
                "iCount"      =>  "0",
                "mc"          =>  $headerarray['DeviceInfo']['@attributes']['mc'],
                "mi"          =>  $headerarray['DeviceInfo']['@attributes']['mi'],
                "nmPoints"    =>  isset($headerarray['Resp']['@attributes']['nmPoints']) ? $headerarray['Resp']['@attributes']['nmPoints'] : "0",
                "pCount"      =>  "0",
                "pType"       =>  "0",
                "qScore"      =>  isset($headerarray['Resp']['@attributes']['qScore'])?$headerarray['Resp']['@attributes']['qScore']:"0",
                "rdsID"       =>  $headerarray['DeviceInfo']['@attributes']['rdsId'],
                "rdsVer"      =>  $headerarray['DeviceInfo']['@attributes']['rdsVer'],
                "sessionKey"  =>  $headerarray['Skey']
            ],
            "cardnumberORUID"       => [
                'adhaarNumber'      => $post->adhaarNumber,
                "indicatorforUID"   => "0",
                "nationalBankIdentificationNumber" => $bank->iinno
            ],
            "languageCode"   => "en",
            "latitude"       => $post->lat,
            "longitude"      => $post->lon,
            "mobileNumber"   => $post->mobileNumber,
            "paymentType"    => "B",
            "requestRemarks" => "Aeps", 
            "timestamp"      => Carbon::now()->format('d/m/Y H:i:s'),
            "transactionType"   => $post->transactionType,
            "merchantUserName"  => $agent->merchantLoginId,
            "merchantPin"       => md5($agent->merchantLoginPin),               
            "subMerchantId"     => ""
        ];

        switch ($post->transactionType) {
            case 'authentication':
                $title = "Aadhar Authentication";
                $url = $this->api->url.'fpaepsservice/auth/tfauth/merchant/php/validate/aadhar';
                $json["merchantTransactionId"] = $post->txnid;
                $json['superMerchantId']   = $post->superMerchantId;
                $json['serviceType'] = $post->serviceType;
                unset($json["paymentType"]);
                unset($json["subMerchantId"]);
                unset($json["timestamp"]);
                break;

            case 'BE':
                $title = "Balance Enquiry";
                $url = $this->api->url.'fpaepsservice/api/balanceInquiry/merchant/php/getBalance';
                $json["merchantTransactionId"] = $post->txnid;
                $json['transactionAmount'] = 0;
                $json['superMerchantId']   = $post->superMerchantId;
                break;

            case 'MS':
                $title = "Mini statement";
                $url = $this->api->url.'fpaepsservice/api/miniStatement/merchant/php/statement';
                $json["merchantTranId"] = $post->txnid;
                $json['transactionAmount'] = 0;
                break;

            case 'CW':
                $checkAmt = ($post->transactionAmount/50);
                if(fmod($checkAmt, 1) !== 0.00){
                    return response()->json(['status' => "ERR", 'message'=> 'Amount Should Be In Multiples Of 50']);
                }

                if(!$agent->auth_cw){
                    return response()->json(['statuscode' => "TXF", "status" => "auth_failed", "message" => "Agent 2FA verification is required"]);
                }

                $title = "Cash Withdrawal";
                $url = $this->api->url.'fpaepsservice/api/cashWithdrawal/merchant/php/withdrawal';
                $json["transactionAmount"] = $post->transactionAmount;
                $json["merchantTranId"]    = $post->txnid;
                $json['superMerchantId']   = $post->superMerchantId;
                break;
                
            case 'M':
                if(!$agent->aadhar_auth_cw){
                    return response()->json(['statuscode' => "TXF", "status" => "auth_failed", "message" => "Agent 2FA verification is required"]);
                }
                $title = "Aadhar Pay";
                $url = $this->api->url.'fpaepsservice/api/aadhaarPay/merchant/php/pay';
                $json["transactionAmount"] = $post->transactionAmount;
                $json["merchantTranId"]    = $post->txnid;
                $json['superMerchantId']   = $post->superMerchantId;
                break;
        }
        
        $txndate = date('d/m/Y H:i:s');
        if(isset($headerarray['DeviceInfo']['additional_info']['Param']['@attributes']['value'])){
            $header = [
                'Content-Type: text/xml',             
                'trnTimestamp:'.$txndate,         
                'hash:'.base64_encode(hash("sha256",json_encode($json), True)),         
                'deviceIMEI:'.$headerarray['DeviceInfo']['additional_info']['Param']['@attributes']['value'],         
                'eskey:'.base64_encode($crypttext)         
            ];
            $deviceIMEI = $headerarray['DeviceInfo']['additional_info']['Param']['@attributes']['value'];
        }else{
            $header = [
                'Content-Type: text/xml',             
                'trnTimestamp:'.date('d/m/Y H:i:s'),         
                'hash:'.base64_encode(hash("sha256",json_encode($json), True)),         
                'deviceIMEI:'.$headerarray['DeviceInfo']['additional_info']['Param'][0]['@attributes']['value'],         
                'eskey:'.base64_encode($crypttext)         
            ];
            $deviceIMEI = $headerarray['DeviceInfo']['additional_info']['Param'][0]['@attributes']['value'];
        }

        if($post->transactionType == "CW" || $post->transactionType == "M" || $post->transactionType == "MS"){
            if($post->transactionType == "CW"){

                if($post->transactionAmount > 0 && $post->transactionAmount <= 500){
                    $provider = Provider::where('recharge1', 'aeps1')->first();
                }elseif($post->transactionAmount>500 && $post->transactionAmount<=2999){
                    $provider = Provider::where('recharge1', 'aeps2')->first();
                }elseif($post->transactionAmount>2999 && $post->transactionAmount<=10000){
                    $provider = Provider::where('recharge1', 'aeps3')->first();
                }
                
                $post['provider_id'] = $provider->id;
                if($post->transactionAmount > 199){
                    $post['profit'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                }else{
                    $post['profit'] = 0;
                }
                $post['tds']    = ($this->api->tds * $post->profit) / 100;
                $post['charge'] = 0;
            }elseif($post->transactionType == "M"){
                if($post->transactionAmount > 0 && $post->transactionAmount <= 10000){
                    $provider = Provider::where('recharge1', 'aadharpay1')->first();
                }
                
                $post['provider_id'] = $provider->id;
                if($post->transactionAmount > 0){
                    $post['charge'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id, $post->provider_id, $user->role->slug);
                }else{
                    $post['charge'] = 0;
                }
                $post['tds']    = 0;
                $post['profit'] = 0;
            }elseif($post->transactionType == "MS"){
                $provider = Provider::where('recharge1', 'ministatement')->first();
                $post['provider_id'] = $provider->id;
                $post['profit'] = \Myhelper::getCommission(0, $user->scheme_id, $post->provider_id, $user->role->slug);
                $post['transactionAmount'] = 0;
                $post['tds']    = ($this->api->tds * $post->profit) / 100;
                $post['charge'] = 0;
            }

            $insert = [
                "mobile"  => $post->mobileNumber,
                "number"  => "XXXXXXXX".substr($post->adhaarNumber, -4),
                "txnid"   => $post->txnid,
                "amount"  => $post->transactionAmount,
                "charge"  => $post->charge,
                "profit"  => $post->profit,
                "tds"     => $post->tds,
                "user_id" => $user->id,
                'status'  => 'pending',
                'credit_by'   => $user->id,
                'trans_type'  => 'credit',
                'balance' => $user->aepswallet,
                'provider_id' => $post->provider_id,
                'api_id'  => $this->api->id,
                'product' => 'aeps',
                'via'     => $post->via,
                'option1' => $post->transactionType,
                'option2' => $headerarray['DeviceInfo']['@attributes']['dpId']."/".$deviceIMEI,
                "option3" => $bank->bankName,
                "option4" => $post->merchantLoginId,
                'option5' => "transaction",
                "option7" => $post->ip(),
                "option8" => $post->lat."/".$post->lon
            ];

            try {
                $report = Aepsreport::create($insert);
            } catch (\Exception $e) {
                return response()->json(['statuscode' => "ERR", "message" => "Technical Issue, Try Again"]);
            }
        }

        $ciphertext_raw = openssl_encrypt(json_encode($json), 'AES-128-CBC', $sessionkey, $options=OPENSSL_RAW_DATA, $iv);
        $request = base64_encode($ciphertext_raw);
        $result  = \Myhelper::curl($url, 'POST', $request, $header, "yes", "Aeps", $post->txnid);
        
        try {
            if($post->transactionType == "M" || $post->transactionType == "CW"){
                $json["captureResponse"] = "";
                \App\Model\Apilog::create([
                    "url" => $url,
                    "modal" => "Aeps",
                    "txnid" => $post->txnid,
                    "header" => $header,
                    "request" => json_encode($json),
                    "response" => $result['response']
                ]);
            }
        } catch (\Exception $e) {
        }

        if($result['response'] == ''){
            $output['statuscode'] = "TUP";
            $output['message'] = "Transaction Under Process";
            $output['txnid']   = $post->txnid;
            $output['balance'] = 0;
            $output['id']      = isset($report->id) ? $report->id : "0";
            $output['rrn']     = "pending";
            $output['date']    = isset($report) ? $report->created_at : date("Y-m-d H:i:s");
            $output['number']  = "XXXXXXXX".substr($post->adhaarNumber, -4);
            $output['provider']= $title;
            $output['status']  = "pending";
            $output['amount']  = $post->transactionAmount;
            $output['transactionType']  = $post->transactionType;
            $output['biller']  = $bank->bankName;
            $output['statement'] = [];
            return response()->json($output);
        }

        $response = json_decode($result['response']);
        if(isset($response->status)){
            switch ($post->transactionType) {
                case "BE":
                case "MS":;
                case "CW":
                case "M":
                    if($post->transactionType == "CW"){
                        Fingagent::where('user_id', $post->user_id)->update(["auth_cw" => null]);
                    }else{
                        Fingagent::where('user_id', $post->user_id)->update(["aadhar_auth_cw" => null]);
                    }

                    if(isset($response->status) && $response->status == true){
                        $outputdata['statuscode'] = "TXN";
                        $outputdata['status']  = "Success";
                        $outputdata['message'] = isset($response->message) ? $response->message : "Transaction Successfull";
                        $outputdata['id']      = isset($response->data->fpTransactionId) ? $response->data->fpTransactionId : '';
                        $outputdata['balance'] = isset($response->data->balanceAmount) ? $response->data->balanceAmount : "0";
                        $outputdata['rrn']     = isset($response->data->bankRRN) ? $response->data->bankRRN : "Not Found";
                        $outputdata['amount']  = $post->transactionAmount;
                        $outputdata['number']  = "XXXXXXXX".substr($post->adhaarNumber, -4);
                        $outputdata['biller']  = $bank->bankName;
                        $outputdata['amount']  = $post->transactionAmount;
                        $outputdata['transactionType'] = $post->transactionType;
                        $outputdata['txnid']   = $post->txnid;
                        $outputdata['date']    = date('d-M-Y h:i A');
                        
                        if($post->transactionType == "MS"){
                            if($response->data->miniOffusFlag === true){
                                foreach ($response->data->miniOffusStatementStructureModel as $ministatement) {
                                    $newstatement = [];
                                    if(str_contains($ministatement, "Balance")){
                                        $stBalance  = explode(":", $ministatement);
                                        $accbalance = $stBalance[1];
                                    }else{
                                        $stBalance  = explode(" ", $ministatement);
                                        $newstatement["date"] = $stBalance[0];
                                        $newstatement["narration"] = $stBalance[1];
                                        $newstatement["amount"] = end($stBalance);
                                        
                                        for ($i=2; $i < sizeof($stBalance); $i++) { 
                                            if($stBalance[$i] == "D"){
                                                $newstatement["txnType"] = "Dr";
                                                break;
                                            }else{
                                                $newstatement["txnType"] = "Cr";
                                            }
                                        }

                                        $outputdata['statement'][] = $newstatement;
                                    }
                                }
                            }else{
                                $outputdata['statement'] = $response->data->miniStatementStructureModel;
                            }
                        }

                        if (in_array($post->transactionType, ["CW", "MS", "M"])) {
                            $update['status'] = "success";
                            $update['refno']  = isset($response->data->bankRRN) ? $response->data->bankRRN : "pending";
                            $update['payid']  = isset($response->data->fpTransactionId) ? $response->data->fpTransactionId : '';
                            $update['description'] = isset($response->data->balanceAmount) ? $response->data->balanceAmount : "0";

                            \DB::transaction(function () use($report, $user, $post, $update) {
                                Aepsreport::where('id', $report->id)->update($update);
                                if($post->transactionType == "CW"){
                                    User::where('id', $user->id)->increment('aepswallet', $post->transactionAmount + ($post->profit - $post->tds));
                                }elseif($post->transactionType == "M"){
                                    User::where('id', $user->id)->increment('aepswallet', $post->transactionAmount - ($post->charge + $post->gst));
                                }elseif($post->transactionType == "MS"){
                                    User::where('id', $user->id)->increment('aepswallet', ($post->profit - $post->tds));
                                }
                            });

                            if($post->transactionType == "CW"){
                                try {
                                    $recon['txnid']           = $post->txnid;
                                    $recon['fpTransactionId'] = ($response->data->fpTransactionId) ? $response->data->fpTransactionId : "Failed";
                                    $recon['bankRRN']         = ($response->data->bankRRN) ? $response->data->bankRRN : "Failed";
                                    $recon['responseCode']    = $response->data->responseCode;
                                    $recon['txndate']         = $txndate;
                                    $recon['transactionType'] = $post->transactionType;
                                    $recon['superMerchantId'] = $post->superMerchantId;
                                    $recon['product']         = "aeps";
                                    $recon['via']             = $post->via;
                                    $recon['env']             = "live";

                                    $this->threewayreconstore($recon);
                                } catch (\Exception $e) {}
                            }
                        }
                    }else{
                        if(isset($response->data->errorMessage)){
                            $message = $response->data->errorMessage;
                        }elseif(isset($response->message)){
                            $message = $response->message;
                        }else{
                            $message = "Transaction Failed";
                        }

                        if (in_array($post->transactionType, ["CW", "MS", "M"])) {
                            $update['status'] = "failed";
                            $update['refno']  = isset($response->data->bankRRN) ? $response->data->bankRRN : "failed";
                            $update['payid']  = isset($response->ackno) ? $response->ackno : "failed";
                            $update['remark'] = $message;
                            Aepsreport::where('id', $report->id)->update($update);
                        }

                        $outputdata['statuscode'] = "TXF";
                        $outputdata['status']  = "Failed";
                        $outputdata['message'] = $message;
                        $outputdata['id']      = isset($response->ackno) ? $response->ackno : "failed";
                        $outputdata['balance'] = isset($response->data->balanceAmount) ? $response->data->balanceAmount : "0";
                        $outputdata['rrn']     = isset($response->data->bankRRN) ? $response->data->bankRRN : "failed";
                        $outputdata['amount']  = $post->transactionAmount;
                        $outputdata['number']  = "XXXXXXXX".substr($post->adhaarNumber, -4);
                        $outputdata['biller']  = $bank->bankName;
                        $outputdata['amount']  = $post->transactionAmount;
                        $outputdata['transactionType'] = $post->transactionType;
                        $outputdata['txnid']   = $post->txnid;
                        $outputdata['date']    = date('d-M-Y h:i A');   
                        if($post->transactionType == "MS"){
                            $outputdata['statement'] = [];
                        } 

                        if(isset($response->message) && $response->message == "Please do 2fa before initiating transaction"){
                            if($post->transactionType == "CW"){
                                Fingagent::where('user_id', $post->user_id)->update(['auth' => "need"]);
                            }else{
                                Fingagent::where('user_id', $post->user_id)->update(['aadhar_auth' => "need"]);
                            }
                            
                            $output['status']  = "auth_failed";
                        }else{
                            $output['status']  = "failed";
                        }

                        if($post->transactionType == "CW"){
                            try {
                                $recon['txnid']           = $post->txnid;
                                $recon['fpTransactionId'] = ($response->data->fpTransactionId) ? $response->data->fpTransactionId : "Failed";
                                $recon['bankRRN']         = ($response->data->bankRRN) ? $response->data->bankRRN : "Failed";
                                if(isset($response->data->responseCode)){
                                    $recon['responseCode'] = $response->data->responseCode;   
                                }elseif(isset($response->statusCode)){
                                    $recon['responseCode'] = $response->statusCode; 
                                }else{
                                    $recon['responseCode'] = "91";
                                }
                                $recon['txndate'] = $txndate;
                                $recon['transactionType'] = $post->transactionType;
                                $recon['superMerchantId'] = $post->superMerchantId;
                                $recon['product'] = "aeps";
                                $recon['via'] = $post->via;
                                $recon['env'] = "live";

                                $this->threewayreconstore($recon);
                            } catch (\Exception $e) {}
                        }
                    }

                    if($post->transactionType == "BE"){
                        $outputdata['provider'] = "Balance Enquiry";
                        $outputdata['statement'] = [];
                    }elseif($post->transactionType == "MS"){
                        $outputdata['provider'] = "Mini Statement";
                        $outputdata['data'] = [];
                    }elseif($post->transactionType == "CW"){
                        $outputdata['provider'] = "Cash Withdrawal";
                    }else{
                        $outputdata['provider'] = "Aadhar Pay";
                    }
                    return response()->json($outputdata);
                    break;
            }        
            return response()->json($output);
        }else{
            $output['statuscode'] = "TUP";
            $output['message'] = "Transaction Under Process";
            $output['txnid']   = $post->txnid;
            $output['balance'] = 0;
            $output['id']      = isset($report->id) ? $report->id : "0";
            $output['rrn']     = "pending";
            $output['date']    = isset($report) ? $report->created_at : date("Y-m-d H:i:s");
            $output['number']  = "XXXXXXXX".substr($post->adhaarNumber, -4);
            $output['provider']= $title;
            $output['status']  = "pending";
            $output['amount']  = $post->transactionAmount;
            $output['biller']  = $bank->bankName;
            $output['transactionType']  = $post->transactionType;
            $output['statement'] = [];

            return response()->json($output);
        }
    }

    public function microatmInitiate(Request $post)
    {
        \DB::table('microlog')->insert(["product" => "matminitiated", 'response' => json_encode($post->all()), 'created_at' => date('Y-m-d H:i:s')]);
        $rules = array(
            'mobile'   => 'required',
            'imei'     => 'required',
            'lat'      => 'required',
            'lon'      => 'required',
            'transactionType' => 'required'
        );
        
        if($post->transactionType == "CW" || $post->transactionType == "M"){
            $rules['transactionAmount'] = 'sometimes|required|numeric|min:100';
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if (!\Myhelper::can('matm_service', $post->user_id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Alloweds"]);
        }

        $user  = User::where('id', $post->user_id)->first();
        $agent = Fingagent::where('user_id', $post->user_id)->first();

        if (!$agent || $agent->status != "approved") {
            return response()->json(['statuscode' => "ERR", "message" => "User onboarding is pending"]);
        }

        $api = Api::where('code', 'fingmatm')->first();
        
        if($api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Service Currently Down."]);
        }
        
        $post['api_id'] = $api->id;
        if(!$api || $api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Service Not Allowed"]);
        }

        do {
            $post['txnid'] = $this->transcode().$post->transactionType.rand(1111111111, 9999999999);
        } while (Aepsreport::where("txnid", "=", $post->txnid)->first() instanceof Aepsreport);

        if($post->transactionType == "CW" || $post->transactionType == "M"){
            $insert = [
                "mobile"  => $post->mobile,
                "number"  => $post->mobile,
                "api_id"  => $post->api_id,
                "txnid"   => $post->txnid,
                "amount"  => $post->transactionAmount,
                "user_id" => $post->user_id,
                "balance" => $user->aepswallet,
                'status'  => 'initiated',
                'payid'   => $post->payid,
                'credit_by'  => $post->user_id,
                'trans_type' => 'credit',
                'product'    => 'matm',
                'option1' => $post->transactionType,
                'option2' => $post->imei,
                'option5' => 'transaction',
                "option7" => $post->ip()."/".$agent->merchantLoginId,
                "option8" => $post->lat."/".$post->lon
            ];

            if($post->transactionAmount > 0 && $post->transactionAmount <= 499){
                $provider = Provider::where('recharge1', 'matm1')->first();
            }elseif($post->transactionAmount>499 && $post->transactionAmount<=999){
                $provider = Provider::where('recharge1', 'matm2')->first();
            }elseif($post->transactionAmount>999 && $post->transactionAmount<=1499){
                $provider = Provider::where('recharge1', 'matm3')->first();
            }elseif($post->transactionAmount>1499 && $post->transactionAmount<=1999){
                $provider = Provider::where('recharge1', 'matm4')->first();
            }elseif($post->transactionAmount>1999 && $post->transactionAmount<=2499){
                $provider = Provider::where('recharge1', 'matm9')->first();
            }elseif($post->transactionAmount>2499 && $post->transactionAmount<=2999){
                $provider = Provider::where('recharge1', 'matm5')->first();
            }elseif($post->transactionAmount>2999 && $post->transactionAmount<=4999){
                $provider = Provider::where('recharge1', 'matm6')->first();
            }elseif($post->transactionAmount>4999 && $post->transactionAmount<=6999){
                $provider = Provider::where('recharge1', 'matm7')->first();
            }elseif($post->transactionAmount>6999 && $post->transactionAmount<=10000){
                $provider = Provider::where('recharge1', 'matm8')->first();
            }
            
            $post['provider_id']   = $provider->id;
            $insert['provider_id'] = $provider->id;
            $insert['profit'] = \Myhelper::getCommission($post->transactionAmount, $user->scheme_id,$post->provider_id, $user->role->slug);
            
            $insert['tds'] = ($api->tds * $insert['profit'])/100;
            Aepsreport::create($insert);
        }

        $output['statuscode']  = "TXN";
        $output['message'] = "Deatils Fetched Successfully";
        $output['data']    = [ 
            "merchantId"       => $agent->merchantLoginId , 
            "merchantPassword" => $agent->merchantLoginPin,
            "superMerchentId"  => $api->optional1,
            "txnid"            => $post->txnid,
            'lat'              => $post->lat,
            'lon'              => $post->lon
        ];
        return response()->json($output);
    }

    public function microatmUpdate(Request $post)
    {
        \DB::table('microlog')->insert(["product" => "matmupdate", 'response' => json_encode($post->all()), 'created_at' => date('Y-m-d H:i:s')]);
        $rules = array(
            'txn_status' => 'required',
            'txnid'      => 'required',
            'transactionType' => "required"
        );
        
        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if (!\Myhelper::can('matm_service', $post->user_id)) {
            return response()->json(['status' => "ERR", "message" => "Service Not Allowed"]);
        }

        $user   = User::where('id', $post->user_id)->first();
        $report = Aepsreport::where('txnid', $post->txnid)->orWhere('txnid', $post->TransId)->where('user_id', $post->user_id)->first();
        $api    = Api::where('code', 'fingmatm')->first();

        if(!$report){
            $output['status'] = "ERR";
            $output['message'] = "Report Not Found";
            return response()->json($output);
        }

        if($report->status != 'initiated'){
            $output['status']  = "ERR";
            $output['message'] = "Permission Not Allowed";
            return response()->json($output);
        }

        $update['payid']   = isset($post->FpId) ? $post->FpId:'';
        $update['refno']   = isset($post->BankRRN) ? $post->BankRRN:'';
        $update['number']  = isset($post->CardNum) ? $post->CardNum:'';
        $update['option3'] = isset($post->BankName) ? $post->BankName:'';
        $update['option4'] = isset($post->CardType) ? $post->CardType:'';
        $update['option2'] = isset($post->TransId)  ? $post->TransId:'';
        $update['remark']  = isset($post->Message)  ? $post->Message:'';
        $update['option6'] = isset($post->statusCode) ? $post->statusCode:'';
        
        if($report->status == "initiated" && $post->txn_status == "success"){
            $update['status'] = "success";
            $credit = Aepsreport::where('id', $report->id)->update($update);
    
            if($credit){
                User::where('id', $user->id)->increment('aepswallet', $report->amount + ($report->profit - $report->tds));
                  
                     \Myhelper::commission($report);
                try {
                    $recon['txnid']   = $post->txnid;
                    $recon['fpTransactionId'] = isset($post->FpId) ? $post->FpId : "Failed";
                    $recon['bankRRN'] = isset($post->BankRRN) ? $post->BankRRN : "Failed";
                    $recon['responseCode']    = "00";
                    $recon['txndate'] = Carbon::createFromFormat('Y-m-d H:i:s', $report->created_at)->format('d/m/Y H:i:s');;
                    $recon['transactionType'] = $post->transactionType;
                    $recon['superMerchantId'] = $api->optional1;
                    $recon['product'] = "matm";
                    $recon['via'] = $post->via;
                    $recon['env'] = "live";

                    $this->threewayreconstore($recon);
                } catch (\Exception $e) {}
            }
            return response()->json(["status" => "success"]);
        }elseif($report->status == "initiated" && $post->txn_status == "failed"){
            $update['status']  = "failed";
            Aepsreport::where('id', $report->id)->update($update);

            try {
                $recon['txnid'] = $post->txnid;
                $recon['fpTransactionId'] = isset($post->FpId) ? $post->FpId : "Failed";
                $recon['bankRRN'] = isset($post->BankRRN) ? $post->BankRRN : "Failed";
                $recon['responseCode'] = isset($post->statusCode)?$post->statusCode:'10004';
                $recon['txndate'] = Carbon::createFromFormat('Y-m-d H:i:s', $report->created_at)->format('d/m/Y H:i:s');
                $recon['transactionType'] = $post->transactionType;
                $recon['superMerchantId'] = $api->optional1;
                $recon['product'] = "matm";
                $recon['via'] = $post->via;
                $recon['env'] = "live";

                $this->threewayreconstore($recon);
            } catch (\Exception $e) {    
            }

            return response()->json(["status" => "failed"]);
        }elseif($report->status == "initiated" && $post->txn_status == "cancel"){
            return response()->json(["status" => "cancel"]);
        }
    }
}
