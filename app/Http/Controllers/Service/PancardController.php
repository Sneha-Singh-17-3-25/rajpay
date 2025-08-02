<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Utiid;
use App\Model\Report;
use App\Model\Provider;
use App\Model\Circle;
use App\User;
use Carbon\Carbon;
use App\Model\Aepsuser;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\JwtGenerator;

class PancardController extends Controller
{
    public function index($type)
    {
        switch ($type) {
            case 'uti':
                if (!\Myhelper::can("pancard_service")) {
                    abort(403);
                }
                $data['vledatas'] = Utiid::where('user_id', \Auth::id())->get();
                return view("service.utipancard")->with($data);
                break;

            case 'nsdl':
                if (!\Myhelper::can("nsdlpancard_service")) {
                    abort(403);
                }
                $data['vledata'] = Utiid::where('user_id', \Auth::id())->first();
                $idprovider      = Provider::where('recharge1', 'pancardid')->first();
                $data['charge']  = \Myhelper::getCommission(0, \Auth::user()->scheme_id, $idprovider->id, \Auth::user()->role->slug);
                return view("service.nsdlpancard")->with($data);
                break;

            default:
                abort(404);
                break;
        }
    }

    public function pancardid(Request $post)
    {
        $provider = Provider::where('recharge1', 'pancardid')->first();
        $post['provider_id'] = $provider->id;
        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "pancard Service Currently Down."]);
        }

        $user = User::where("id", $post->user_id)->first();
        if($user->status != "active"){
            return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
        }
        
        $post['amount'] = \Myhelper::getCommission(0, $user->scheme_id, $post->provider_id, $user->role->slug);

        if($post->amount == 0){
            return response()->json(['statuscode' => "ERR", "message" => 'Please contact your service provider']);
        }

        if($user->mainwallet < $post->amount){
            return response()->json(['statuscode' => "ERR", "message" => 'Low Balance, Kindly recharge your wallet.']);
        }
        $previousrecharge = Report::where('number', $user->mobile)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
        if($previousrecharge > 0){
            return response()->json(['statuscode' => "ERR", "message" => 'Same Transaction allowed after 2 min.']);
        }

        do {
            $post['txnid'] = "UTIID".rand(1111111111, 9999999999);
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        $insert = [
            'number'  => $user->mobile,
            'mobile'  => $user->mobile,
            'provider_id' => $provider->id,
            'api_id'  => $provider->api->id,
            'amount'  => $post->amount,
            'txnid'   => $post->txnid,
            'option1' => "idcharge",
            'status'  => 'pending',
            'user_id'    => $user->id,
            'credit_by'  => $user->id,
            'rtype'      => 'main',
            'via'        => $post->via,
            'balance'    => $user->mainwallet,
            'trans_type' => 'debit',
            'product'    => 'pancard',
            'create_time'=> $user->id."-".date('ymdhis'),
            "option7" => $post->ip()."/".$_SERVER['HTTP_USER_AGENT'],
            "option8" => $post->lat."/".$post->lon
        ];

        try {
            $report = \DB::transaction(function () use($insert, $post) {
                $report = Report::create($insert);
                User::where('id', $insert['user_id'])->decrement('mainwallet', $post->amount);
                return $report;
            });
        } catch (\Exception $e) {
            $report = false;
        }

        if (!$report){
            return response()->json(['statuscode' => "ERR", "message" => 'Transaction Failed, please try again.']);
        }

        $post['vleid'] = $post->txnid;
        $post['vlepassword'] = $post->txnid;
        $post['name']  = $user->name;
        $post['location'] = $user->city;
        $post['pincode']  = $user->pincode;
        $post['idtype']   = "nsdl";
        $action = Utiid::create($post->all());
        if ($action) {
            return response()->json(['statuscode' => "TXN", "message" => "Vle id created successfully"]);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
        }
    }

    public function vleid(Request $post)
    {
        if(!$post->has("pancard")){
            $user = User::where("id", $post->user_id)->first();
            do {
                $post['txnid'] = "VM".$user->mobile;
            } while (Utiid::where("txnid", "=", $post->txnid)->first() instanceof Utiid);

            $post['vleid'] = $post->txnid;
            $post['vlepassword'] = $post->txnid;
            $post['name']  = $user->name;
            $post['shopname'] = $user->shopname;
            $post['pincode']  = $user->pincode;
            $post['pancard']  = $user->pancard;
            $post['aadhar']   = $user->aadhar;
            $post['state']    = $user->state;
            $post['mobile']   = $user->mobile;
            $post['email']    = $user->email;
            $post['location'] = $user->city;
        }else{
            do {
                $post['txnid'] = "MUTIID".$post->mobile;
            } while (Utiid::where("txnid", "=", $post->txnid)->first() instanceof Utiid);

            $rules = array(
                'name'     => 'required',
                'shopname' => 'required',
                'pincode'  => 'required',
                'pancard'  => 'required',
                "state"    => "required",
                "mobile"   => "required",
                "email"    => "required",
                "city"     => "required",
            );
            
            $validator = \Validator::make($post->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->errors()], 422);
            }

            $post['vleid'] = $post->txnid;
            $post['vlepassword'] = $post->txnid;
        }
        
        $post['idtype'] = "uti";
        $action = Utiid::create($post->all());
        if ($action) {
            return response()->json(['statuscode' => "TXN", "message" => "Vle id created successfully"]);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
        }
    }

    public function payment(Request $post)
    {
        dd($post->all());
        if (isset($permission) && !\Myhelper::can("pancard_service")) {
            return response()->json(['status' => "Permission Not Allowed"]);
        }
 
        $rules = array(
            'lastname' => 'required',
            'description' => 'required',
            'title'    => 'required',
            'mobile'   => 'required',
            'email'    => 'required',
            "consent"  => "required",
            "option2"  => "required",
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $provider = Provider::where('recharge1', 'pancard')->first();
        $post['provider_id'] = $provider->id;
        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "pancard Service Currently Down."]);
        }

        $user = User::where("id", $post->user_id)->first();
        if($user->status != "active"){
            return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
        }
        
        $post['tokens'] = 1;
        if($user->mainwallet < $post->tokens * 107){
            return response()->json(['statuscode' => "ERR", "message" => 'Low Balance, Kindly recharge your wallet.']);
        }
        $vledata = Utiid::where('user_id', \Auth::id())->first();

        $previousrecharge = Report::where('number', $post->mobile)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
        if($previousrecharge > 0){
            return response()->json(['statuscode' => "ERR", "message" => 'Same Transaction allowed after 2 min.']);
        }
        
        $post['amount'] = $post->tokens * 107;
        $post['profit'] = $post->tokens * \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);

        do {
            $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        $action = User::where('id', $post->user_id)->decrement('mainwallet', $post->amount - $post->profit);
        if ($action) {
            $insert = [
                'number'  => $post->mobile,
                'mobile'  => $user->mobile,
                'provider_id' => $provider->id,
                'api_id'  => $provider->api->id,
                'amount'  => $post->amount,
                'profit'  => $post->profit,
                'txnid'   => $post->txnid,
                'option1' => $post->option1,
                'option2' => $post->option2,
                'option3' => $post->tokens,
                'option4' => $post->firstname." ".$post->middlename." ".$post->lastname,
                'option5' => $post->email,
                'option6' => $post->category,
                'option7' => $post->pancard,
                'description' => $post->description,
                'status'  => 'pending',
                'user_id'    => $user->id,
                'credit_by'  => $user->id,
                'rtype'      => 'main',
                'via'        => $post->via,
                'balance'    => $user->mainwallet,
                'trans_type' => 'debit',
                'product'    => 'pancard',
                'create_time'=> $user->id."-".date('ymdhis'),
                "option8" => $post->lat."/".$post->lon
            ];

            $report = Report::create($insert);

            $formData = array(
                "applicantDto" => array(
                    "appliType" => $post->option1,
                    "category"  => $post->category,
                    "title"     => $post->title,
                    "lastName"  => $post->lastname,
                    "firstName" => $post->firstname,
                    "middleName"=> $post->middlename,  
                    "applnMode" => $post->description,
                ),
                "otherDetails"  => array(
                    "phyPanIsReq" => "",
                ),
                "telOrMobNo" => $post->mobile, 
            );

            if($post->category == "CR"){
                $formData["applicantDto"]["pan"] = $post->pancard;
            }

            $parameterData = array(
                "reqEntityData"=> array(
                    "txnid"           => $post->txnid,
                    "branchCode"      => "EM".$user->id,
                    "entityId"        => "AchariyaTechno",
                    "dscProvider"     => "Verasys CA 2014",
                    "dscSerialNumber" => "2B 32 60 77",
                    "dscExpiryDate"   => "4-3-24",
                    "returnUrl" => url("callback/nsdlpan"),
                    "formData"  => base64_encode(json_encode($formData)),
                    "reqTs"     => date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes")),
                    "authKey"   => "AchariyaTechno",
                ),
                "signature"=>""
            );

            if($post->category == "A"){
                $type = "F";
            }else{
                $type = "C";
                $parameterData["reqEntityData"]["reqType"] = "CR";
            }
        
            $url = "https://admin.e-banker.in/api/nsdl/signature?type=".$type."&data=".base64_encode(json_encode($parameterData));
            $signature = \Myhelper::curl($url, "POST", "", [], "no");
            if($post->category == "A"){
                $data = substr(json_decode(json_encode($signature["response"]), true), 38);
            }else{
                $data = substr(json_decode(json_encode($signature["response"]), true), 43);
            }

            $query["req"] = $data;
            return response()->json(['statuscode' => "TXN", "data" => $query["req"], "panData" => $formData, "requestData" => $data , 'pfx'=>'new']);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
        }
    }
    
    // public function payment(Request $post)
    // {
    //     if (isset($permission) && !\Myhelper::can("pancard_service")) {
    //         return response()->json(['status' => "Permission Not Allowed"]);
    //     }
 
    //     $rules = array(
    //         'lastname' => 'required',
    //         'description' => 'required',
    //         'title'    => 'required',
    //         'mobile'   => 'required',
    //         'email'    => 'required',
    //         "consent"  => "required",
    //         "option2"  => "required",
    //     );
        
    //     $validator = \Validator::make($post->all(), $rules);
    //     if ($validator->fails()) {
    //         return response()->json(['errors'=>$validator->errors()], 422);
    //     }

    //     $provider = Provider::where('recharge1', 'pancard')->first();
    //     $post['provider_id'] = $provider->id;
    //     if(!$provider){
    //         return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
    //     }

    //     if($provider->status == 0){
    //         return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
    //     }

    //     if(!$provider->api || $provider->api->status == 0){
    //         return response()->json(['statuscode' => "ERR", "message" => "pancard Service Currently Down."]);
    //     }

    //     $user = User::where("id", $post->user_id)->first();
    //     if($user->status != "active"){
    //         return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
    //     }
        
    //     $post['tokens'] = 1;
    //     if($user->mainwallet < $post->tokens * 107){
    //         return response()->json(['statuscode' => "ERR", "message" => 'Low Balance, Kindly recharge your wallet.']);
    //     }
    //     $vledata = Utiid::where('user_id', \Auth::id())->first();

    //     $previousrecharge = Report::where('number', $post->mobile)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
    //     if($previousrecharge > 0){
    //         return response()->json(['statuscode' => "ERR", "message" => 'Same Transaction allowed after 2 min.']);
    //     }
        
    //     $post['amount'] = $post->tokens * 107;
    //     $post['profit'] = $post->tokens * \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);

    //     do {
    //         $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
    //     } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

    //     $action = User::where('id', $post->user_id)->decrement('mainwallet', $post->amount - $post->profit);
    //     if ($action) {
    //         $insert = [
    //             'number'  => $post->mobile,
    //             'mobile'  => $user->mobile,
    //             'provider_id' => $provider->id,
    //             'api_id'  => $provider->api->id,
    //             'amount'  => $post->amount,
    //             'profit'  => $post->profit,
    //             'txnid'   => $post->txnid,
    //             'option1' => $post->option1,
    //             'option2' => $post->option2,
    //             'option3' => $post->tokens,
    //             'option4' => $post->firstname." ".$post->middlename." ".$post->lastname,
    //             'option5' => $post->email,
    //             'option6' => $post->category,
    //             'option7' => $post->pancard,
    //             'description' => $post->description,
    //             'status'  => 'pending',
    //             'user_id'    => $user->id,
    //             'credit_by'  => $user->id,
    //             'rtype'      => 'main',
    //             'via'        => $post->via,
    //             'balance'    => $user->mainwallet,
    //             'trans_type' => 'debit',
    //             'product'    => 'pancard',
    //             'create_time'=> $user->id."-".date('ymdhis'),
    //             "option8" => $post->lat."/".$post->lon
    //         ];

    //         $report = Report::create($insert);

    //         $formData = array(
    //             "applicantDto" => array(
    //                 "appliType" => $post->option1,
    //                 "category"  => $post->category,
    //                 "title"     => $post->title,
    //                 "lastName"  => $post->lastname,
    //                 "firstName" => $post->firstname,
    //                 "middleName"=> $post->middlename,  
    //                 "applnMode" => $post->description,
    //             ),
    //             "otherDetails"  => array(
    //                 "phyPanIsReq" => "",
    //             ),
    //             "telOrMobNo" => $post->mobile, 
    //         );

    //         if($post->category == "CR"){
    //             $formData["applicantDto"]["pan"] = $post->pancard;
    //         }

    //         $parameterData = array(
    //             "reqEntityData"=> array(
    //                 "txnid"           => $post->txnid,
    //                 "branchCode"      => "EM".$user->id,
    //                 "entityId"        => "AchariyaTechno",
    //                 "dscProvider"     => "Verasys CA 2014",
    //                 "dscSerialNumber" => "2B 32 60 77",
    //                 "dscExpiryDate"   => "4-3-24",
    //                 "returnUrl" => url("callback/nsdlpan"),
    //                 "formData"  => base64_encode(json_encode($formData)),
    //                 "reqTs"     => date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes")),
    //                 "authKey"   => "AchariyaTechno",
    //             ),
    //             "signature"=>""
    //         );

    //         if($post->category == "A"){
    //             $type = "F";
    //         }else{
    //             $type = "C";
    //             $parameterData["reqEntityData"]["reqType"] = "CR";
    //         }

    //         try {
    //             // Convert to JSON without escaping slashes
    //             $reqEntityJson = json_encode($parameterData, JSON_UNESCAPED_SLASHES);
                
    //             // Base64 encode the full request entity JSON
    //             $base64Request = base64_encode($reqEntityJson);

    //             // Sign the request using Java utility
    //             $certPath = public_path('YESPAL.pfx');
    //             $certPassword = env('CERT_PASSWORD', '123456789');
    //             $jarPath = public_path('Assissted_Signing_v3.jar');
    //             $alias = 'yespal singh'; // Certificate alias
    //             $flag = $type;

    //             // Prepare Java command for signing
    //             $command = "java -jar \"$jarPath\" \"$base64Request\" \"$certPath\" \"$certPassword\" \"$alias\" \"$flag\" 2>&1";

    //             // Execute signing command
    //             $signedRequest = shell_exec($command);

    //             // Extract signature from response
    //             $startPos = strpos($signedRequest, '{');
    //             $endPos = strrpos($signedRequest, '}');

    //             $data = null;
    //             if ($startPos !== false && $endPos !== false) {
    //                 $fullResponse = substr($signedRequest, $startPos, $endPos - $startPos + 1);
                    
    //                 // Extract data similar to original logic
    //                 if($post->category == "A"){
    //                     $data = substr($fullResponse, 38);
    //                 }else{
    //                     $data = substr($fullResponse, 43);
    //                 }
    //             }

    //             if (!$data) {
    //                 throw new Exception('Failed to extract signature data');
    //             }

    //             $query["req"] = $data;
    //             return response()->json(['statuscode' => "TXN", "data" => $query["req"], "panData" => $formData, "requestData" => $data]);

    //         } catch (Exception $e) {
    //             return response()->json(['statuscode' => "ERR", "message" => "Signing failed: " . $e->getMessage()]);
    //         }
    //     }else{
    //         return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
    //     }
    // }
    private function extractSignature($logString) {
      $startPos = strpos($logString, '{');
    $endPos = strrpos($logString, '}');

    if ($startPos === false || $endPos === false) {
        return null; // No JSON found
    }

    // Extract the JSON substring
    $jsonString = substr($logString, $startPos, $endPos - $startPos + 1);

    // Decode JSON into array
    // $data = json_decode($jsonString, true);
    // dd($jsonString);
    // Return decoded array or null if decoding fails
    return $jsonString ?: null;
    }

    public function utipayment(Request $post)
    {
        if (isset($permission) && !\Myhelper::can("pancard_service")) {
            return response()->json(['status' => "Permission Not Allowed"]);
        }
 
        $rules = array(
            'tokens' => 'required|numeric|min:1',
            'vleid'  => 'required',
        );
        
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $provider = Provider::where('recharge1', 'pancard')->first();
        $post['provider_id'] = $provider->id;
        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "pancard Service Currently Down."]);
        }

        $user  = User::where("id", $post->user_id)->first();
        $vleid = Utiid::where("user_id", $post->user_id)->where('vleid', $post->vleid)->first();

        if(!$vleid){
            return response()->json(['statuscode' => "ERR", "message" => "Vle Id not found"]);
        }

        if($vleid->status != "success"){
            return response()->json(['statuscode' => "ERR", "message" => "Vle Id status is ". $vleid->status ]);
        }
        
        if($user->mainwallet - $user->lockedamount < $post->tokens * 107){
            return response()->json(['statuscode' => "ERR", "message" => 'Low Balance, Kindly recharge your wallet.']);
        }

        $previousrecharge = Report::where('number', $vleid->vleid)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
        if($previousrecharge > 0){
            return response()->json(['statuscode' => "ERR", "message" => 'Same Transaction allowed after 2 min.']);
        }
        
        $post['amount'] = $post->tokens * 107;
        $post['profit'] = $post->tokens * \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
        $post['tds']    = ($post->profit * $provider->api->tds) / 100;

        do {
            $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        $insert = [
            'number'  => $vleid->vleid,
            'mobile'  => $user->mobile,
            'provider_id' => $provider->id,
            'api_id'  => $provider->api->id,
            'amount'  => $post->amount,
            'profit'  => $post->profit,
            'tds'     => $post->tds,
            'txnid'   => $post->txnid,
            'option1' => $post->tokens,
            'status'  => 'pending',
            'user_id'    => $user->id,
            'credit_by'  => $user->id,
            'rtype'      => 'main',
            'via'        => $post->via,
            'balance'    => $user->mainwallet,
            'trans_type' => 'debit',
            'product'    => 'pancard',
            'create_time'=> $user->id."-".date('ymdhis'),
            "option7" => $post->ip()."/".$_SERVER['HTTP_USER_AGENT'],
            "option8" => $post->lat."/".$post->lon
        ];

        try {
            $report = \DB::transaction(function () use($insert, $post) {
                $report = Report::create($insert);
                User::where('id', $insert['user_id'])->decrement('mainwallet', $post->amount - ($post->profit - $post->tds));
                return $report;
            });
        } catch (\Exception $e) {
            $report = false;
        }

        if (!$report){
            return response()->json(['statuscode' => "ERR", "message" => 'Transaction Failed, please try again.']);
        }

        \Myhelper::commission($report);
        $output['statuscode'] = "TXN";
        $output['message'] = "Uti Pan Token Request Submitted";
        $output['txnid']   = $post->txnid;
        $output['id']      = $report->id;
        $output['rrn']     = $post->txnid;
        $output['date']    = date("d-M-Y");
        $output['number']  = $report->number;
        $output['provider']= $provider->name;
        $output['status']  = "pending";
        $output['amount']  = $post->amount;
        return response()->json($output);
    }

    public function encrypt($text, $passphrase){
        $salt   = openssl_random_pseudo_bytes(8);
        $salted = $dx = '';

        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32); 
        $iv  = substr($salted, 32, 16);
        return base64_encode('Salted__' . $salt . openssl_encrypt($text . '', 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv)); 
    }

    public function decrypt($encrypted, $passphrase) {
        $encrypted = base64_decode($encrypted); 
        $salted    = substr($encrypted, 0, 8) == 'Salted__';

        if (!$salted) { return null; }
        $salt = substr($encrypted, 8, 8); 
        $encrypted = substr($encrypted, 16);
        $salted = $dx = '';

        while (strlen($salted) < 48) {
            $dx = md5($dx . $passphrase . $salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32); 
        $iv  = substr($salted, 32, 16);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, true, $iv);
    }
    
    public function nsdlupdate(Request $post)
    {
        \DB::table('microlog')->insert(["product" => 'nsdl', 'response' => json_encode($post->all())]);

        if($post->has("txnStatus")){
            $data["txnid"] = $post->txnid;
            $report = Report::where('txnid', $post->txnid)->where("status", "pending")->first();
            
            if($report){
                if($post->txnStatus == "1"){
                    $data['status'] = "success";
                    $data['refno']  = $post->ackNo;
                    $data['remark'] = $post->ackNo;
                }else{
                    $data['status'] = "failed";
                    $data['refno']  = "failed";
                    $data['remark'] = isset($post->errorMsg) ? $post->errorMsg : "Failed";
                }

                if(isset($data['status'])){
                    Report::where('txnid', $post->txnid)->update([
                        "status" => $data['status'],
                        "refno"  => $data['refno'],
                        "remark" => $data['remark']
                    ]);
                }
            }else{
                return redirect(url('pancard/nsdl'));
            }
        }
        return view("errors.receipt")->with($data);
    }
}
