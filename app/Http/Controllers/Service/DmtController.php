<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Api;
use App\Model\Provider;
use App\Model\Mahabank;
use App\Model\Report;
use App\Model\Commission;
use App\Model\Packagecommission;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DmtController extends Controller
{
    protected $api;
    public function __construct()
    {
        $this->api = Api::where('code', 'avenue_dmt')->first();
    }

    public function index()
    {
        if (\Myhelper::hasRole('admin') || !\Myhelper::can('dmt1_service')) {
            abort(403);
        }

        $data['banks'] = \DB::table('dmtbanks')->get();
        $data['state'] = \DB::table('circles')->get();
        return view('service.dmt1')->with($data);
    }

    public function payment(Request $post)
    {
        if(!$this->api || $this->api->status == 0){
            return response()->json(['statuscode' => 'ERR', 'message' => "Money Transfer Service Currently Down."]);
        }

        $userdata = User::where('id', $post->user_id)->first();
        if($post->type == "FundTransfer"){
            $codes = ['dmt1', 'dmt2', 'dmt3', 'dmt4', 'dmt5'];
            $providerids = [];
            foreach ($codes as $value) {
                $providerids[] = Provider::where('recharge1', $value)->first(['id'])->id;
            }
            $commission = Commission::where('scheme_id', $userdata->scheme_id)->whereIn('slab', $providerids)->get();
            if(!$commission || sizeof($commission) < 5){
                return response()->json(['statuscode' => 'ERR', 'message' => "Money Transfer charges not set, contact administrator."]);
            }
        }

        switch ($post->type) {
            case 'getbanks':
                return response()->json(['statuscode' => 'TXN', 'message' => 'Bank List Fetched Successfully', 'data' => \DB::table('dmt_mahabanks')->orderBy('bankname', 'asc')->get()]);
                break;

            case 'SenderDetails':
                $rules = array('mobile' => 'required|numeric|digits:10');
                break;

            case 'SenderRegister':
                $rules = array('user_id' => 'required|numeric', 'mobile' => 'required|numeric|digits:10', 'fname' => 'required|regex:/^[\pL\s\-]+$/u', 'lname' => 'required|regex:/^[\pL\s\-]+$/u', 'pincode' => "required|numeric|digits:6");
                break;

            case 'VerifySender':
                $rules = array('user_id' => 'required|numeric', 'mobile' => 'required|numeric|digits:10', 'otp' => 'required|numeric|digits:6');
                break;

            case 'ResendSenderOtp':
                $rules = array('user_id' => 'required|numeric', 'mobile' => 'required|numeric|digits:10');
                break;

            case 'AllRecipient':
                $rules = array('mobile' => 'required|numeric|digits:10');
                break;

            case 'GetRecipient':
                $rules = array('user_id' => 'required|numeric','mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20", "benemobile" => 'required|numeric|digits:10', "benename" => "required|regex:/^[\pL\s\-]+$/u");
                break;

            case 'RegRecipient':
                $rules = array('mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20", "benemobile" => 'required|numeric|digits:10', "benename" => "required");
                break;

            case 'DelRecipient':
                $rules = array('user_id' => 'required|numeric','recipient' => 'required','mobile' => 'required|numeric|digits:10');
                break;

            case 'BankList':
                $rules = array();
                break;

            case 'VerifyBankAcct':
                $rules = array('mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20");
                break;

            case 'GetCCFFee':
                $rules = array('amount' => 'required|numeric|min:100|max:25000');
                break;

            case 'FundTransfer':
                $rules = array('amount' => 'required|numeric|min:10|max:25000','name' => 'required', 'mobile' => 'required|numeric|digits:10', 'benebank' => 'required', 'beneifsc' => "required", 'beneaccount' => "required|numeric|digits_between:6,20", "benename" => "required", "rec_id" => "required");
                break;

            case 'MultiTxnStatus':
                $rules = array('urefid' => 'required');
                break;

            default:
                return ['statuscode'=>'BPR', "status" => "Bad Parameter Request2", 'message'=> "Invalid request format"];
                break;
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        do {
            $post['txnid'] = "RJPDMT".Str::random(17).sprintf("%012d", substr(date("Y"), -1).date("z").date('Hs'));
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        switch ($post->type) {
            case 'SenderDetails':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>SenderDetails</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'SenderRegister':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>SenderRegister</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                                <senderName>$post->fname $post->lname</senderName>
                                <senderPin>$post->pincode</senderPin>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'VerifySender':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>VerifySender</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                                <otp>$post->otp</otp>
                                <additionalRegData>$post->regdata</additionalRegData>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'ResendSenderOtp':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>ResendSenderOtp</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'AllRecipient':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>AllRecipient</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'GetRecipient':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>GetRecipient</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                                <recipientId>$post->recipient</recipientId>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'RegRecipient':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $benename = strtoupper($post->benename);
                $beneifsc = strtoupper($post->beneifsc);
                $parameter = "<dmtServiceRequest>
                                <requestType>RegRecipient</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                                <recipientName>$benename</recipientName>
                                <recipientMobileNumber>$post->benemobile</recipientMobileNumber>
                                <bankCode>$post->benebank</bankCode>
                                <bankAccountNumber>$post->beneaccount</bankAccountNumber>
                                <ifsc>$beneifsc</ifsc>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'DelRecipient':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>DelRecipient</requestType>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <txnType>IMPS</txnType>
                                <recipientId>$post->recipient</recipientId>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'BankList':
                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>BankList</requestType>
                                <txnType>IMPS</txnType>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'VerifyBankAcct':
                $provider = Provider::where('recharge1', 'dmt1accverify')->first();
                $post['charge'] = \Myhelper::getCommission($post->amount, $userdata->scheme_id, $provider->id, $userdata->role->slug);
                $post['adminprofit'] = \Myhelper::getAdminCommission($post->amount, $provider->id);
                $post['provider_id'] = $provider->id;
                $post['amount'] = 1;
                if ($userdata->mainwallet < $post->amount + $post->charge) {
                    return response()->json(["statuscode" => "IWB", 'message' => 'Low balance, kindly recharge your wallet.']);
                }

                $url = $this->api->url."dmt/dmtServiceReq/xml?";
                $parameter = "<dmtServiceRequest>
                                <requestType>VerifyBankAcct</requestType>
                                <agentId>CC01AV34AGTU00000011</agentId>
                                <initChannel>AGT</initChannel>
                                <senderMobileNumber>$post->mobile</senderMobileNumber>
                                <bankCode>$post->benebank</bankCode>
                                <bankAccountNumber>$post->beneaccount</bankAccountNumber>
                                <ifsc>$post->beneifsc</ifsc>
                            </dmtServiceRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'GetCCFFee':
                $url = $this->api->url."dmt/dmtTransactionReq/xml?";
                $amount = $post->amount*100;
                $parameter = "<dmtTransactionRequest>
                                <requestType>GetCCFFee</requestType>
                                <agentId>CC01AV34AGTU00000011</agentId>
                                <txnAmount>$amount</txnAmount>
                            </dmtTransactionRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            case 'FundTransfer':

                $pincheck = $this->pinCheck($post);

                if ($pincheck == "fail") {
                    return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is incorrect"]);
                }

                if ($pincheck == "block") {
                    return response()->json(['statuscode' => "ERR", "message" => "Transaction Pin is block, reset tpin"]);
                }
                return $this->transfer($post, $userdata);
                break;

            case 'MultiTxnStatus':
                $url = $this->api->url."dmt/dmtTransactionReq/xml?";
                $parameter = "<dmtTransactionRequest>
                                <requestType>MultiTxnStatus</requestType>
                                <agentId>CC01AV34AGTU00000011</agentId>
                                <initChannel>AGT</initChannel>
                                <uniqueRefId>$post->urefid</uniqueRefId>
                            </dmtTransactionRequest>";
                $encrypt_xml_data = \Myhelper::encrypt($parameter, $this->api->password);
                break;

            default:
                return response()->json(['statuscode'=> 'BPR', 'status'=> 'Bad Parameter Request1','message'=> "Bad Parameter Request"]);
                break;
        }

        $data['accessCode'] = $this->api->username;
        $data['requestId']  = $post->txnid;
        $data['encRequest'] = $encrypt_xml_data;
        $data['ver'] = "1.0";
        $data['instituteId'] = $this->api->optional1;
        $parameters = http_build_query($data);

        $result = \Myhelper::curl($url, "POST", $parameters, [], "no");
        
        $response_data = \Myhelper::decrypt($result['response'], $this->api->password);
        $xml      = simplexml_load_string($response_data, "SimpleXMLElement", LIBXML_NOCDATA);
        $response = json_decode(json_encode($xml), true);

        switch ($post->type) {
            case 'SenderDetails':
                if (isset($response['responseCode']) && $response['responseCode'] == '000' && $response['mobileVerified'] == 'true') {
                    return response()->json(['statuscode'=> 'TXN', 'message'=> 'Transaction Successfull','data'=> $response]);
                }

                if (isset($response['errorInfo']) && $response['errorInfo']['error']['errorCode'] == 'DMT050') {
                    return response()->json(['statuscode'=> 'RNF', 'message'=> 'Sender Data Not Found' , 'data'=> $response['errorInfo']['error']['errorMessage']]);
                }

                if (isset($response['errorInfo']) && $response['errorInfo']['error']['errorCode'] == 'DMT004') {
                    return response()->json(['statuscode'=> 'RNF', 'message'=> 'Invalid mobile number' , 'data'=> $response['errorInfo']['error']['errorMessage']]);
                }
                
                if (isset($response['mobileVerified']) && $response['mobileVerified'] == "false") {
                    return response()->json(['statuscode'=> 'RNF', 'message'=> 'Sender Data Not Found' , 'data'=> 'Sender Data Not Found']);
                }
                break;

            case 'SenderRegister':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    return response()->json(['statuscode'=> 'TXNOTP', 'status'=> 'Transaction Successfull','message'=> $response]);
                }else {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response]);
                }
                break;

            case 'VerifySender':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull','message'=> $response]);
                } else {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response]);
                }
                break;

            case 'ResendSenderOtp':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull','message'=> $response]);
                } elseif (isset($response['errorInfo']) && $response['errorInfo']['error']['errorCode'] == 'DMT050') {
                    return response()->json(['statuscode'=> 'RNF', 'status'=> 'Sender Data Not Found' , 'message'=> $response['errorInfo']['error']['errorMessage']]);
                } else {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response]);
                }
                break;

            case 'AllRecipient':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    
                    if(isset($response['recipientList']['dmtRecipient'][0])){
                        return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull1', 'message'=> $response['recipientList']]);
                    }elseif(isset($response['recipientList']['dmtRecipient'])){
                        return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull2', 'message'=> ["dmtRecipient" => [$response['recipientList']['dmtRecipient']]]]);
                    }else{
                        return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull2', 'message'=> $response['recipientList']]);
                    }
                } elseif (isset($response['errorInfo'])) {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error', 'message'=> $response['errorInfo']['error']['errorMessage']]);
                } else {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error', 'message'=> $response]);
                }
                break;

            case 'GetRecipient':
                dd($response);
                break;

            case 'RegRecipient':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    $bank = \DB::table("dmt_mahabanks")->where("bankcode", $post->benebank)->first();
                    return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull', 'message'=> $response]);
                } elseif (isset($response['errorInfo']) && $response['errorInfo']['error']['errorCode'] == 'DMT042') {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error', 'message'=> $response['errorInfo']['error']['errorMessage']]);
                } else{
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error', 'message'=> $response['errorInfo']['error']['errorMessage']]);
                }
                break;

            case 'DelRecipient':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull']);
                } elseif (isset($response['errorInfo'])) {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error', 'message'=> $response['errorInfo']['error']['errorMessage']]);
                } else {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error']);
                }
                break;

            case 'BankList':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull','message'=> $response['bankList']['bankInfoArray']]);
                }
                break;

            case 'VerifyBankAcct':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    $balance = User::where('id', $userdata->id)->first(['mainwallet']);

                    $insert = [
                        'api_id'  => $this->api->id,
                        'provider_id' => $post->provider_id,
                        'mobile'  => $post->mobile,
                        'number'  => $post->beneaccount,
                        'option2' => isset($response['impsName']) ? $response['impsName'] : $post->benename,
                        'option3' => $post->benebank,
                        'option4' => $post->beneifsc,
                        'txnid'   => isset($response['txnId']) ? $response['txnId'] : '',
                        'refno'   => isset($response['uniqueRefId']) ? $response['uniqueRefId'] : "none",
                        'amount'  => $post->amount,
                        'adminprofit'  => $post->adminprofit,
                        'charge'  => $post->charge,
                        'remark'  => "Money Transfer",
                        'status'  => 'success',
                        'user_id' => $userdata->id,
                        'credit_by'   => $userdata->id,
                        'product' => 'dmt',
                        'balance' => $balance->mainwallet,
                        'description' => $post->benemobile,
                        'trans_type'  => 'debit',
                        'via'     => $post->via,
                        "option7" => $post->ip()."/".$_SERVER['HTTP_USER_AGENT'],
                        "option8" => $post->gps_location
                    ];

                    $report = Report::create($insert);
                    if($report){
                        User::where('id', $post->user_id)->decrement('mainwallet', $post->charge + $post->amount);
                        return response()->json(['statuscode'=> 'TXN', 'status'=> 'Transaction Successfull','message'=> $response]);
                    }else{
                        return response()->json(['statuscode'=> 'TXR', 'message'=> 'Something went wrong']);
                    }

                } elseif (isset($response['errorInfo']) && isset($response['errorInfo']['error']['errorCode'])) {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response['errorInfo']['error']['errorMessage'], 'error_code' => $response['errorInfo']['error']['errorCode']]);
                } else {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response]);
                }
                break;

            case 'GetCCFFee':
                if (isset($response['responseCode']) && $response['responseCode'] == '000') {
                    return $response['custConvFee'];
                } elseif (isset($response['errorInfo']) && $response['errorInfo']['error']['errorCode'] == 'DMT058') {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response['errorInfo']['error']['errorMessage']]);
                } elseif (isset($response['errorInfo']) && $response['errorInfo']['error']['errorCode'] == 'DMT062') {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response['errorInfo']['error']['errorMessage']]);
                } else {
                    return response()->json(['statuscode'=> 'TXR', 'status'=> 'Transaction Error' , 'message'=> $response]);
                }
                break;
                
            default:
                return response()->json(['statuscode'=> 'BPR', 'status'=> 'Bad Parameter Request','message'=> "Bad Parameter Request"]);
                break;
        }
    }

    public function transfer($post)
    {
        $rules = array(
            'user_id'  => 'required|numeric',
            'name'     => 'required',
            'mobile'   => 'required|numeric|digits:10',
            'benebank' => 'required',
            'beneifsc' => "required", 
            'beneaccount' => "required|numeric|digits_between:6,20",
            "benename" => "required",
            'amount'   => 'required|numeric|min:100|max:25000'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        $user = User::where('id', $post->user_id)->first();

        $outputs['statuscode'] = "TXN";
        $outputs['date']   = date("d-M-y");
        $outputs['amount'] = $post->amount;

        $url = $this->api->url."dmt/dmtTransactionReq/xml?";
        $data['accessCode'] = $this->api->username;
        $data['ver'] = "1.0";
        $data['instituteId'] = $this->api->optional1;

        $amount = $post->amount;
        for ($i=1; $i < 6; $i++) { 
            if(5000*($i-1) <= $amount  && $amount <= 5000*$i){
                if($amount == 5000*$i){
                    $n = $i;
                }else{
                    $n = $i-1;
                    $x = $amount - $n*5000;
                }
                break;
            }
        }

        $amounts = array_fill(0,$n,5000);
        if(isset($x)){
            array_push($amounts , $x);
        }

        foreach ($amounts as $amount) {
            $post['amount'] = $amount;
            $post['charge'] = $this->getCharge($post->amount);

            if($this->getAccBalance($user->id, 'mainwallet') < $post->amount + $post->charge){
                $outputs['data'][] = array(
                    'amount'  => $amount,
                    'status'  => 'failed',
                    'refno'   => 'Insufficient Wallet Balance',
                    'txnid'   => date('ymdhis'),
                    "message" => "Insufficient Wallet Balance"
                );
            }else{
                $previousrecharge = Report::where('number', $post->beneaccount)->where('amount', $post->amount)->where('provider_id', $post->provider_id)->whereBetween('create_time', [Carbon::now()->subSeconds(1)->format('Y-m-d H:i:s'), Carbon::now()->addSeconds(1)->format('Y-m-d H:i:s')])->count();

                if($previousrecharge){
                    $outputs['data'][] = array(
                        'amount'  => $amount,
                        'status'  => 'failed',
                        'refno'   => 'failed',
                        'txnid'   => date('ymdhis'),
                        "message" => "Same Transaction Repeat",
                    );
                }else{
                    do {
                        $post['txnid'] = "RJPDMTT".Str::random(16).sprintf("%012d",substr(date("Y"), -1).date("z").date('Hs'));
                    } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

                    if($post->amount >= 100 && $post->amount <= 1000){
                        $provider = Provider::where('recharge1', 'dmt1')->first();
                    }elseif($amount>1000 && $amount<=2000){
                        $provider = Provider::where('recharge1', 'dmt2')->first();
                    }elseif($amount>2000 && $amount<=3000){
                        $provider = Provider::where('recharge1', 'dmt3')->first();
                    }elseif($amount>3000 && $amount<=4000){
                        $provider = Provider::where('recharge1', 'dmt4')->first();
                    }else{
                        $provider = Provider::where('recharge1', 'dmt5')->first();
                    }

                    $post['provider_id'] = $provider->id;

                    $charge = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
                    $post['tds'] = ($this->api->tds * ($post->charge - $charge))/100;

                    $post['debitAmount'] = $post->amount + $charge + $post->tds;
                    $insert = [
                        'api_id'  => $this->api->id,
                        'provider_id' => $post->provider_id,
                        'option1' => $post->name,
                        'mobile'  => $post->mobile,
                        'number'  => $post->beneaccount,
                        'option2' => $post->benename,
                        'option3' => $post->benebank,
                        'option4' => $post->beneifsc,
                        'txnid'   => $post->txnid,
                        'amount'  => $post->amount,
                        'charge'  => $post->charge,
                        'profit'  => $post->charge - $charge,
                        'tds'     => $post->tds,
                        'via'     => $post->via,
                        'status'  => 'success',
                        'user_id' => $user->id,
                        'credit_by' => $user->id,
                        'product' => 'dmt',
                        'balance' => $this->getAccBalance($user->id, 'mainwallet'),
                        'description' => $post->benemobile,
                        'trans_type'  => 'debit',
                        'create_time' => $user->id."-".date('ymdhis'),
                        "option7" => $post->ip(),
                        "option8" => $post->gps_location
                    ];

                    try {
                        $report = \DB::transaction(function () use($insert, $post) {
                            $report = Report::create($insert);
                            User::where('id', $insert['user_id'])->decrement('mainwallet', $post->debitAmount);
                            return $report;
                        });
                    } catch (\Exception $e) {
                        \DB::table('log_500')->insert([
                            'line' => $e->getLine(),
                            'file' => $e->getFile(),
                            'log'  => $e->getMessage(),
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        $report = false;
                    }

                    if(!$report){
                        $outputs['data'][] = array(
                            'amount'  => $amount,
                            'status'  => 'failed',
                            'refno'   => 'Transaction Failed',
                            'txnid'   => date('ymdhis'),
                            "message" => "Transaction Failed",
                        );
                    }else{
                        try {
                            $parameter = "<dmtTransactionRequest>
                                <requestType>FundTransfer</requestType>
                                <agentId>CC01AC93AGTU00000001</agentId>
                                <initChannel>AGT</initChannel>
                                <txnType>IMPS</txnType>
                                <senderMobileNo>".$post->mobile."</senderMobileNo>
                                <recipientId>".$post->rec_id."</recipientId>
                                <txnAmount>".($post->amount*100)."</txnAmount>
                                <convFee>".($post->charge*100)."</convFee>
                            </dmtTransactionRequest>";
                            $encrypt_xml_data   = \Myhelper::encrypt($parameter, $this->api->password);

                            $data['requestId']  = $post->txnid;
                            $data['encRequest'] = $encrypt_xml_data;
                            $parameters         = http_build_query($data);
                            $result             = \Myhelper::curl($url, "POST", $parameters, [], "yes", "report", $post->txnid);

                            $response_data = \Myhelper::decrypt($result['response'], $this->api->password);
                            $xml      = simplexml_load_string($response_data, "SimpleXMLElement", LIBXML_NOCDATA);
                            $response = json_decode(json_encode($xml), true);

                            if (isset($response['responseCode']) && !isset($response['errorInfo'])) {
                                if($response['responseCode'] == '200' && isset($response['respDesc'])){
                                    Report::where('id', $report->id)->update([
                                        'status'=> 'failed',
                                        "refno"  => $response['responseReason'],
                                        "remark" => $response['respDesc']
                                    ]);
                                    User::where('id', $post->user_id)->increment('mainwallet', $post->debitAmount);
                                    $outputs['data'][] = array(
                                        'amount'  => $post->amount,
                                        'status'  => 'failed',
                                        'refno'   => $report->txnid,
                                        'txnid'   => $report->txnid,
                                        "message" => $response['responseReason'],
                                    );
                                }elseif($response['responseCode'] == '000'){
                                    Report::where('id', $report->id)->update([
                                        'status'  => "success",
                                        'payid'   => $response['fundTransferDetails']['fundDetail']['refId'],
                                        'refno'   => $response['fundTransferDetails']['fundDetail']['bankTxnId'],
                                        'option6' => $response['fundTransferDetails']['fundDetail']['DmtTxnId']
                                    ]);
                                    \Myhelper::commission($report, "reports");

                                    $outputs['data'][] = array(
                                        'amount'  => $post->amount,
                                        'status'  => 'success',
                                        'refno'   => $response['fundTransferDetails']['fundDetail']['bankTxnId'],
                                        'txnid'   => $report->txnid,
                                        "message" => 'Transaction Successfull',
                                    );
                                }else{
                                    Report::where('id', $report->id)->update([
                                        'status' => "pending",
                                        'payid'  => $response['fundTransferDetails']['fundDetail']['refId'],
                                        'refno'  => $response['fundTransferDetails']['fundDetail']['bankTxnId'],
                                        'option6' => isset($response['fundTransferDetails']['fundDetail']['DmtTxnId'])?$response['fundTransferDetails']['fundDetail']['DmtTxnId']:"none"
                                    ]);
                                    \Myhelper::commission($report, "reports");

                                    $outputs['data'][] = array(
                                        'amount'  => $post->amount,
                                        'status'  => 'pending',
                                        'refno'   => $response['fundTransferDetails']['fundDetail']['bankTxnId'],
                                        'txnid'   => $report->txnid,
                                        "message" => 'Transaction Successfull',
                                    );
                                }
                            }elseif(isset($response['errorInfo'])){
                                Report::where('id', $report->id)->update([
                                    'status' => 'failed',
                                    'refno'  => $response['errorInfo']['error']['errorMessage']
                                ]);
                                User::where('id', $post->user_id)->increment('mainwallet', $post->debitAmount);
                                $outputs['data'][] = array(
                                    'amount'  => $post->amount,
                                    'status'  => 'failed',
                                    'refno'   => $response['errorInfo']['error']['errorMessage'],
                                    'txnid'   => $report->txnid,
                                    "message" => $response['errorInfo']['error']['errorMessage']
                                );
                            }else{
                                Report::where('id', $report->id)->update([
                                    'status' => 'pending',
                                    'refno'  => 'pending'
                                ]);
                                \Myhelper::commission($report, "reports");

                                $outputs['data'][] = array(
                                    'amount'  => $post->amount,
                                    'status'  => 'pending',
                                    'refno'   => $report->txnid,
                                    'txnid'   => $report->txnid,
                                    "message" => "pending",
                                );
                            }
                        } catch (\Exception $e) {
                            \DB::table('log_500')->insert([
                                'line' => $e->getLine(),
                                'file' => $e->getFile(),
                                'log'  => $e->getMessage(),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);

                            if(isset($report)){
                                Report::where('id', $report->id)->update([
                                    'status' => "pending",
                                    'payid'  => "pending" ,
                                    'refno'  => "pending",
                                    'remark' => "pending"
                                ]);

                                \Myhelper::commission($report, "reports");
                                $outputs['data'][] = array(
                                    'amount'  => $amount,
                                    'status'  => 'success',
                                    'refno'   => $report->txnid,
                                    'txnid'   => date('ymdhis'),
                                    "message" => "Transaction Under Process",
                                );
                            }else{
                                User::where('id', $user->id)->increment('mainwallet', $post->debitAmount);
                                $outputs['data'][] = array(
                                    'amount'  => $amount,
                                    'status'  => 'failed',
                                    'refno'   => 'failed',
                                    'txnid'   => date('ymdhis'),
                                    "message" => "Same Transaction Repeat",
                                );
                            }
                        }
                    }
                }
            }
            sleep(1);
        }
        return response()->json($outputs, 200);
    }

    public function getCharge($amount)
    {
        if ($amount < 1000) {
            return 10;
        } else {
            return $amount*1/100;
        }
    }

    public function getGst($amount)
    {
        return $amount*100/118;
    }

    public function getTds($amount)
    {
        return $amount*5/100;
    }
}
