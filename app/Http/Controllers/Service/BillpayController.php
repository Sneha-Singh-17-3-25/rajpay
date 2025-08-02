<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Provider;
use App\Model\Report;
use Carbon\Carbon;
use App\Model\Api;
use App\Model\Bbpsagent;
use Illuminate\Support\Str;
use App\User;
use Spatie\ArrayToXml\ArrayToXml;

class BillpayController extends Controller
{
    protected $billapi;
    public function __construct()
    {
        $this->billapi = Api::where('code', 'bbps_avenue')->first();
    }

    public function index(Request $post, $type)
    {
        if (\Myhelper::hasRole('admin') || !\Myhelper::can('billpay_service')) {
            abort(403);
        }
        
        $data['type'] = $type;
        $post['user_id']   = \Auth::id();

        $bbpsagent = Bbpsagent::where("user_id", $post->user_id)->first();
        if($post->has("gps_location") && $post->gps_location != ""){
            $geocode = $post->gps_location;
        }else{
            $geocode = $post->ip_location;
        }
        $user = \Auth::user();
        
        $alternatecodeValue = null;
        $alternatecode      = \DB::table("bbpsagents")->whereNotNull("alternatecode")->orderBy("id", "dsec")->first();

        if($alternatecode){
            $agentid    = \DB::table("bbpsagents")->where("agentid", $alternatecode->alternatecode)->first();
            if($agentid){
                $nextid = \DB::table("bbpsagents")->whereNotNull("agentid")->where("id", ">", $agentid->id)->first(); 
                if($nextid){
                    $alternatecodeValue = $nextid->agentid;
                }else{
                    $first = \DB::table("bbpsagents")->whereNotNull("agentid")->first();
                    if($first){
                        $alternatecodeValue = $first->agentid;
                    }
                }
            }
        }
        
        if(!$bbpsagent){
            Bbpsagent::create([
                'user_id' => $user->id,
                'name'    => $user->name,
                'mobile'  => $user->mobile,
                'shopname'=> $user->shopname,
                'city'    => $user->city,
                'address' => $user->address,
                'pincode' => $user->pincode,
                'state'   => $user->state,
                'geocode' => $geocode,
                'status'  => "approved",
                'alternatecode' => $alternatecodeValue,
                "created_at" => date("Y-m-d H:i:s")
            ]);
        }else{
            if($bbpsagent->geocode == ""){
                Bbpsagent::where("user_id", $user->id)->update([
                    'name'    => $user->name,
                    'mobile'  => $user->mobile,
                    'shopname'=> $user->shopname,
                    'city'    => $user->city,
                    'address' => $user->address,
                    'pincode' => $user->pincode,
                    'state'   => $user->state,
                    'geocode' => $geocode,
                    'alternatecode' => $alternatecodeValue,
                    'status'  => "approved",
                    "created_at" => date("Y-m-d H:i:s")
                ]);
            }
        }

        return view('service.billpayment')->with($data);
    }

    public function providersList(Request $post)
    {
        $billPayType = explode(",", $post->billPayType);
        $providers = \DB::table("providers")->where('type', $post->type)->whereIn('billPayType', $billPayType)->where('status', "1")->orderBy("name", "asc")->get();
        return response()->json(['statuscode' => "TXN", 'message' => "Provider Fetched Successfully", 'data' => $providers]);
    }
    
    public function providersDetails(Request $post)
    {
        $providers = Provider::where('id', $post->provider_id)->first();
        return response()->json(['statuscode' => "TXN", 'message' => "Provider Fetched Successfully", 'data' => $providers]);
    }

    public function fetchBill(Request $post)
    {
        if (!\Myhelper::can('billpay_service', $post->user_id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
        }
        
        $rules = array(
            'provider_id' => 'required',
        );
        
        $user = User::where('id', $post->user_id)->first();
        if($user->status != "active"){
            return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
        }

        $provider = Provider::where('id', $post->provider_id)->first();
        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Bill Payment Service Currently Down."]);
        }

        for ($i=0; $i < sizeof($provider->paramname); $i++) {
            $rules['number'.$i] = "required";
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $npciagent = \DB::table('bbpsagents')->where('user_id', $post->user_id)->first();
        if(!$npciagent){
            $alternatecodeValue = null;
            $alternatecode = \DB::table("bbpsagents")->whereNotNull("alternatecode")->orderBy("id", "dsec")->first();

            if($alternatecode){
                $agentid = \DB::table("bbpsagents")->where("agentid", $alternatecode->alternatecode)->first();
                if($agentid){
                    $nextid = \DB::table("bbpsagents")->whereNotNull("agentid")->where("id", ">", $agentid->id)->first(); 
                    if($nextid){
                        $alternatecodeValue = $nextid->agentid;
                    }else{
                        $first = \DB::table("bbpsagents")->whereNotNull("agentid")->first();
                        if($first){
                            $alternatecodeValue = $first->agentid;
                        }
                    }
                }
            }

            $npciagent = Bbpsagent::create([
                'user_id' => $user->id,
                'name'    => $user->name,
                'mobile'  => $user->mobile,
                'shopname'=> $user->shopname,
                'city'    => $user->city,
                'address' => $user->address,
                'pincode' => $user->pincode,
                'state'   => $user->state,
                'geocode' => $post->gps_location,
                'status'  => "pending",
                "created_at" => date("Y-m-d H:i:s")
            ]);
        }else{
            if($npciagent->geocode == ""){
                Bbpsagent::where("user_id", $user->id)->update([
                    'name'    => $user->name,
                    'mobile'  => $user->mobile,
                    'shopname'=> $user->shopname,
                    'city'    => $user->city,
                    'address' => $user->address,
                    'pincode' => $user->pincode,
                    'state'   => $user->state,
                    'geocode' => $post->gps_location,
                    'status'  => "pending",
                    "created_at" => date("Y-m-d H:i:s")
                ]);
            }
        } 

        if(!$npciagent->agentid || $npciagent->agentid == "null" || $npciagent->agentid == null){
            $agent = $npciagent->alternatecode;
        }else{
            $agent = $npciagent->agentid;
        }

        do {
            $post['txnid'] = "RJPBBPS".Str::random(16).sprintf("%012d", substr(date("Y"), -1).date("z").date('Hs'));
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        $url = $this->billapi->url."extBillCntrl/billFetchRequest/xml";

        $parameter = '<billFetchRequest>
            <agentId>'.$agent.'</agentId>
            <agentDeviceInfo>
                <ip>101.53.145.140</ip>
                <initChannel>AGT</initChannel>
                <mac>01-23-45-67-89-ab</mac>
            </agentDeviceInfo>
            
            <customerInfo>
                <customerMobile>'.$user->mobile.'</customerMobile>
                <customerEmail></customerEmail>
                <customerAdhaar></customerAdhaar>
                <customerPan></customerPan>
            </customerInfo>
        <billerId>'.$provider->recharge1.'</billerId><inputParams>';
        
        $number = "";
        for ($i = 0; $i < sizeof($provider->paramname); $i++){
            $parameter .= '
            <input>
            <paramName>'.$provider->paramname[$i].'</paramName>
            <paramValue>'.$post['number'.$i].'</paramValue>
            </input>';
            $number .= $post['number'.$i]."|";
        }

        $parameter .= '
            </inputParams></billFetchRequest>';
        $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->billapi->password);
        
        $data['accessCode']  = $this->billapi->username;
        $data['requestId']   = $post->txnid;
        $data['encRequest']  = $encrypt_xml_data;
        $data['ver']         = "1.0";
        $data['instituteId'] = $this->billapi->optional1;
        $parameters          = http_build_query($data);

        $result = \Myhelper::curl($url, 'POST', $parameters, [], 'no');

        if($result['response'] == ""){
            return response()->json(['statuscode' => "ERR", "message" => "Bill Not Found"]);
        }

        $responsedata = \Myhelper::decrypt($result['response'], $this->billapi->password);     
        $xml      = simplexml_load_string($responsedata);
        $response = json_decode(json_encode((array) $xml), true);
        
        if(isset($response['responseCode'])){                    
            if($response['responseCode']=="000"){
                
                if(isset($response['additionalInfo']['info']['4']['infoValue'])){
                    $balance = $response['additionalInfo']['info']['4']['infoValue'];
                }else{
                    $balance = 0;
                }

                \DB::table("billdetails")->insert([
                    "user_id"     => $user->id,
                    "provider"    => $provider->id,
                    "number"      => trim($number, "|"),
                    'billAmount'  => ($response['billerResponse']['billAmount']/100),
                    'customerName'=> is_array($response['billerResponse']['customerName'])? "" : $response['billerResponse']['customerName'],
                    'dueDate'     => isset($response['billerResponse']['dueDate'])?$response['billerResponse']['dueDate']:"",
                    'requestId'   => $data['requestId'],
                    'billerResponse' => $result['response'],
                    "created_at"  => date("Y-m-d H:i:s")
                ]);

                return response()->json([
                    'statuscode' => "TXN",
                    'data'       => [
                        "customername" => $response['billerResponse']['customerName'],
                        "duedate"      => isset($response['billerResponse']['dueDate'])?$response['billerResponse']['dueDate']:"",
                        "dueamount"    => ($response['billerResponse']['billAmount']/100),
                        "TransactionId"=> $data['requestId'],
                        'balance'      => $balance,
                    ]
                ]);
            }else{
                return response()->json(['statuscode' => "ERR", "message" => $response['errorInfo']['error']['errorMessage']]);
            }
        }else{
            return response()->json(['statuscode' => "ERR", "message" => "Bill Not Found"]);
        }
    }

    public function validateBill(Request $post)
    {
        $npciagent = \DB::table('bbpsagents')->where('user_id', $post->user_id)->first();
        if(!$npciagent->agentid || $npciagent->agentid == "null" || $npciagent->agentid == null){
            $agent = $npciagent->alternatecode;
        }else{
            $agent = $npciagent->agentid;
        }

        $provider  = Provider::where('id', $post->provider_id)->first();
        do {
            $post['txnid'] = "RJPBBPS".Str::random(16).sprintf("%012d", substr(date("Y"), -1).date("z").date('Hs'));
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        $url = $this->billapi->url."extBillValCntrl/billValidationRequest/xml";

        $parameter = '<billValidationRequest>
            <agentId>'.$agent.'</agentId>
            <agentDeviceInfo>
                <ip>101.53.145.140</ip>
                <initChannel>AGT</initChannel>
                <mac>01-23-45-67-89-ab</mac>
            </agentDeviceInfo>
            <billerId>'.$provider->recharge1.'</billerId><inputParams>';
        
        $number = "";

        for ($i=0; $i < sizeof($provider->paramname); $i++) { 
            $parameter .= '
            <input>
            <paramName>'.$provider->paramname[$i].'</paramName>
            <paramValue>'.$post['number'.$i].'</paramValue>
            </input>';
            $number .= $post['number'.$i]."|";
        }

        $parameter .= '</inputParams></billValidationRequest>';
        $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->billapi->password);
        
        $data['accessCode'] = $this->billapi->username;
        $data['requestId']  = $post->txnid;
        $data['encRequest'] = $encrypt_xml_data;
        $data['ver'] = "1.0";
        $data['instituteId'] = $this->billapi->optional1;
        $parameters  = http_build_query($data);

        $result = \Myhelper::curl($url, 'POST', $parameters, [], 'no');

        if($result['response'] == ""){
            return response()->json(['statuscode' => "ERR", "message" => "Bill Not Found"]);
        }

        $responsedata = \Myhelper::decrypt($result['response'], $this->billapi->password);     
        $xml      = simplexml_load_string($responsedata);
        $response = json_decode(json_encode((array) $xml), true);
        
        if(isset($response['responseCode'])){                      
            if($response['responseCode']=="000"){
                return [
                    'statuscode'    => 'TXN', 
                    'TransactionId' => $post->txnid
                ];
            }else{
                return ['statuscode' => "ERR", "message" => $response['errorInfo']['error']['errorMessage']];
            }
        }else{
            return ['statuscode' => "ERR", "message" => "Bill Not Found"];
        }
    }
    
    public function payment(Request $post)
    {
        if (!\Myhelper::can('billpay_service', $post->user_id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
        }
        
        $rules = array(
            "provider_id"   => 'required'
        );

        $user = User::where('id', $post->user_id)->first();
        if($user->status != "active"){
            return response()->json(['statuscode' => "ERR", "message" => "Your account has been blocked."]);
        }

        $npciagent = \DB::table('bbpsagents')->where('user_id', $post->user_id)->first();
        if(!$npciagent){
            $alternatecodeValue = null;
            $alternatecode = \DB::table("bbpsagents")->whereNotNull("alternatecode")->orderBy("id", "dsec")->first();

            if($alternatecode){
                $agentid = \DB::table("bbpsagents")->where("agentid", $alternatecode->alternatecode)->first();
                if($agentid){
                    $nextid = \DB::table("bbpsagents")->whereNotNull("agentid")->where("id", ">", $agentid->id)->first(); 
                    if($nextid){
                        $alternatecodeValue = $nextid->agentid;
                    }else{
                        $first = \DB::table("bbpsagents")->whereNotNull("agentid")->first();
                        if($first){
                            $alternatecodeValue = $first->agentid;
                        }
                    }
                }
            }

            $npciagent = Bbpsagent::create([
                'user_id' => $user->id,
                'name'    => $user->name,
                'mobile'  => $user->mobile,
                'shopname'=> $user->shopname,
                'city'    => $user->city,
                'address' => $user->address,
                'pincode' => $user->pincode,
                'state'   => $user->state,
                'geocode' => $post->gps_location,
                'status'  => "pending",
                "created_at" => date("Y-m-d H:i:s")
            ]);
        }else{
            if($npciagent->geocode == ""){
                Bbpsagent::where("user_id", $user->id)->update([
                    'name'    => $user->name,
                    'mobile'  => $user->mobile,
                    'shopname'=> $user->shopname,
                    'city'    => $user->city,
                    'address' => $user->address,
                    'pincode' => $user->pincode,
                    'state'   => $user->state,
                    'geocode' => $post->gps_location,
                    'status'  => "pending",
                    "created_at" => date("Y-m-d H:i:s")
                ]);
            }
        } 

        if(!$npciagent->agentid || $npciagent->agentid == "null" || $npciagent->agentid == null){
            $agent = $npciagent->alternatecode;
        }else{
            $agent = $npciagent->agentid;
        }

        $provider = Provider::where('id', $post->provider_id)->first();
        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Not Found"]);
        }

        if($provider->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Operator Currently Down."]);
        }

        if(!$provider->api || $provider->api->status == 0){
            return response()->json(['statuscode' => "ERR", "message" => "Bill Payment Service Currently Down."]);
        }

        for ($i=0; $i < sizeof($provider->paramname); $i++) {
            $rules['number'.$i] = "required";
        }
        
        $rules['amount'] = "required";
        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if($provider->billPayType == "fetchpay"){
            $fetchResponse = \DB::table("billdetails")->where("requestId", $post->TransactionId)->first();
            if(!$fetchResponse){
                return response()->json(['statuscode' => "ERR", "message" => "Bill fetch is required"]);
            }
            $rules['TransactionId'] = "required";
            
            if($provider->billerPaymentExactness == "exactandbelow" && $post->amount > $fetchResponse->billAmount){
                return response()->json(['statuscode' => "ERR", "message" => "Maximum allowed amount is ".$fetchResponse->billAmount]);
            }elseif($provider->billerPaymentExactness == "exactandabove" && $post->amount < $fetchResponse->billAmount){
                return response()->json(['statuscode' => "ERR", "message" => "Minimum allowed amount is ".$fetchResponse->billAmount]);
            }elseif($provider->billerPaymentExactness == "exact"){
                $post['amount'] = $fetchResponse->billAmount;
            }
            $quickPay = "N";
        }elseif($provider->billPayType == "quickpay"){
            do {
                $post['TransactionId'] = "RJPBBPS".Str::random(16).sprintf("%012d", substr(date("Y"), -1).date("z").date('Hs'));
            } while (Report::where("txnid", "=", $post->TransactionId)->first() instanceof Report);
            $quickPay = "Y";
        }elseif($provider->billPayType == "validatepay"){
            if(!$post->has("TransactionId")){
                $post['type'] = "validatebilldetail";
                $validateResponse = $this->validateBill($post);

                if($validateResponse['statuscode'] != "TXN"){
                    return response()->json($validateResponse);
                }
                $post['TransactionId'] = $validateResponse['TransactionId'];
            }
        }

        $rules['amount'] = "required";
        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(['statuscode' => "ERR", "message" => $error]);
        }

        if($user->mainwallet < $post->amount){
            return response()->json(['statuscode' => "ERR", "message" => 'Low Balance, Kindly recharge your wallet.']);
        }

        $previousrecharge = Report::where('number', $post->number)->where('amount', $post->amount)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
        if($previousrecharge > 0){
            return response()->json(['statuscode' => "ERR", "message" => 'Same Transaction allowed after 2 min.']);
        }

        $post['profit'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
        $post['tds']    = ($post->profit * $provider->api->tds)/100;
        $debit = User::where('id', $user->id)->decrement('mainwallet', $post->amount - ($post->profit - $post->tds));
        if($debit) {
            do {
                $post['txnid'] = $this->transcode().rand(1111111111, 9999999999);
            } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

            $insert = [
                'number'   => $post->number0,
                'mobile'   => isset($post->number1)?$post->number1:$user->mobile,
                'provider_id' => $provider->id,
                'api_id'   => $provider->api->id,
                'amount'   => $post->amount,
                'profit'   => $post->profit,
                'tds'      => $post->tds,
                'txnid'    => $post->txnid,
                'apitxnid' => $post->apitxnid,
                'option1'  => $post->biller,
                'option2'  => $post->duedate,
                'status'   => 'pending',
                'user_id'    => $user->id,
                'credit_by'  => $user->id,
                'rtype'      => 'main',
                'via'        => 'portal',
                'balance'    => $user->mainwallet,
                'trans_type' => 'debit',
                'product'    => 'billpay',
                'create_time'=> Carbon::now()->format('Y-m-d H:i:s'),
                "option7" => $post->ip(),
                "option8" => $post->lat."/".$post->lon
            ];
            try {
                $report = Report::create($insert);
            } catch (\Exception $e) {
                User::where('id', $user->id)->increment('mainwallet', $post->amount - ($post->profit - $post->tds));
                return response()->json(['statuscode' => "ERR", "message" => 'Same Transaction allowed after 2 min.']);
            }
            
            $parameter = '<billPaymentRequest>
                <agentId>'.$agent.'</agentId>
                <billerAdhoc>'.strtolower($provider->billerAdhoc).'</billerAdhoc>
                <agentDeviceInfo>
                    <ip>101.53.145.140</ip>
                    <initChannel>AGT</initChannel>
                    <mac>01-23-45-67-89-ab</mac>
                </agentDeviceInfo>
                
                <customerInfo>
                    <customerMobile>'.$user->mobile.'</customerMobile>
                    <customerEmail></customerEmail>
                    <customerAdhaar></customerAdhaar>
                    <customerPan></customerPan>
                </customerInfo>
            <billerId>'.$provider->recharge1.'</billerId><inputParams>';
            
            for ($i=0; $i < sizeof($provider->paramname); $i++) { 
                $parameter .= '
                <input>
                <paramName>'.$provider->paramname[$i].'</paramName>
                <paramValue>'.$post['number'.$i].'</paramValue>
                </input>';
            }
            $parameter .= "</inputParams>";

            if($provider->billPayType == "fetchpay"){
                $responsedata = \App\Helpers\Encryption::decrypt($fetchResponse->billerResponse, $this->billapi->password);
                $xml      = simplexml_load_string($responsedata);
                $billerresponse = json_decode(json_encode((array) $xml), true);
                
                $parameter .= str_replace('<?xml version="1.0"?>', "", ArrayToXml::convert($billerresponse['billerResponse'], 'billerResponse'));
                if(isset($billerresponse['additionalInfo'])){
                    $parameter .= str_replace('<?xml version="1.0"?>', "", ArrayToXml::convert($billerresponse['additionalInfo'], 'additionalInfo'));
                }
            }
            
            $parameter .= '<amountInfo>
                <amount>'.($post->amount*100).'</amount>
                <currency>356</currency>
                <custConvFee>0</custConvFee>
                <amountTags></amountTags>';

            $parameter .= '</amountInfo>
            <paymentMethod>
                <paymentMode>Cash</paymentMode>
                <quickPay>'.$quickPay.'</quickPay>
                <splitPay>N</splitPay>
            </paymentMethod>
            <paymentInfo>
                <info>
                <infoName>Remarks</infoName>
                <infoValue>Received</infoValue>
                </info>
            </paymentInfo>';
            $parameter .= '</billPaymentRequest>';

            $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->billapi->password);
            
            $data['accessCode'] = $this->billapi->username;
            $data['requestId']  = $post->TransactionId;
            $data['encRequest'] = $encrypt_xml_data;
            $data['ver'] = "1.0";
            $data['instituteId'] = $this->billapi->optional1;
            $parameters = http_build_query($data);
            $url = $this->billapi->url."extBillPayCntrl/billPayRequest/xml";

            $result = \Myhelper::curl($url, "POST", $parameters, [], "yes", "App\Model\Report", $post->TransactionId);

            if($result['error'] || $result['response'] == ''){
                $update['status'] = "pending";
                $update['refno']  = "pending";
                $update['description'] = "billpayment pending";
            }else{
                $responsedata = \Myhelper::decrypt($result['response'], $this->billapi->password);
                        
                $xml = simplexml_load_string($responsedata);
                $doc = json_decode(json_encode((array) $xml), true);
                
                if(isset($doc['responseCode'])){
                    if($doc['responseCode'] == "000"){
                        $update['status']  = "success";
                        $update['refno']   = $doc['txnRefId'];
                        $update['payid']   = $doc['txnRefId'];
                        $update['option3'] = $doc['approvalRefNumber'];
                        $update['description'] = "billpayment accepted";
                    }elseif($doc['responseCode'] == "999" && $doc['responseReason'] == "Awaited"){
                        $update['status']  = "pending";
                        $update['refno']   = $doc['txnRefId'];
                        $update['payid']   = $doc['txnRefId'];
                        $update['description'] = "billpayment accepted";
                    }else{
                        $update['status'] = "failed";
                        $update['refno'] = isset($doc['errorInfo']['error']['errorMessage']) ? $doc['errorInfo']['error']['errorMessage'] : "Failed";
                        $update['description'] = "billpayment failed";
                    }
                }else{
                    $update['status'] = "pending";
                    $update['payid'] = "pending";
                    $update['description'] = "billpayment pending";
                }
            }

            if($update['status'] == "success" || $update['status'] == "pending"){
                Report::where('id', $report->id)->update($update);
                \Myhelper::commission($report);
                $output['statuscode'] = "TXN";
                $output['status'] = "Success";
                $output['message'] = "Billpayment Request Submitted";
            }else{
                User::where('id', $user->id)->increment('mainwallet', $post->amount - ($post->profit - $post->tds));
                Report::where('id', $report->id)->update($update);
                $output['statuscode'] = "TXF";
                $output['status'] = "Failed";
                $output['message'] = $update['refno'];    
            }
            $output['txnid']      = $post->txnid;
            $output['date']       = date("d-M-Y");
            $output['amount']     = $post->amount;
            $output['refno']      = $update['refno'];
            $output['number']     = $post->number0;
            $output['order_id']   = $report->id;
            $output['provider']   = $provider->name;
            $output['biller']     = $post->biller;
            return response()->json($output);
        }else{
            return response()->json(['statuscode' => "ERR", "message" => 'Transaction Failed, please try again.']);
        }
    }
}
