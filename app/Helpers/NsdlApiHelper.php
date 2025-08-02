<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Model\Report;
use App\Model\Provider;
use Carbon\Carbon;

class NsdlApiHelper
{
    /**
     * Process NSDL agent KYC registration
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public static function processAgentKyc(Request $request)
    {
        // Check if a pending agent exists for the user
        $bankagent = DB::table('emitra_agents')
            ->where('user_id', $request->user_id)
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
            'agentPhoneNumber' => 'required|numeric|digits:10',
            'userPan'          => 'required',
            'agentPinCode'     => 'nullable|numeric|digits:6',
            // Additional fields
            'middlename'       => 'nullable',
            'alternatenumber'  => 'nullable|numeric|digits:10',
            'telephone'        => 'nullable',
            'district'         => 'nullable',
            'area'             => 'nullable',
            'shopaddress'      => 'nullable',
            'shopstate'        => 'nullable',
            'shopcity'         => 'nullable',
            'shopdistrict'     => 'nullable',
            'shoparea'         => 'nullable',
            'shoppincode'      => 'nullable|numeric|digits:6',
            'dmt'              => 'nullable',
            'aeps'             => 'nullable',
            'cardpin'          => 'nullable',
            'accountopen'      => 'nullable',
            'tposserialno'     => 'nullable',
            'temail'           => 'nullable|email',
            'taddress'         => 'nullable',
            'taddress1'        => 'nullable',
            'tstate'           => 'nullable',
            'tcity'            => 'nullable',
            'tpincode'         => 'nullable|numeric|digits:6',
            'agenttype'        => 'nullable',
            'agentbcid'        => 'nullable',
        ];
        
        // Validate request
        $validate = \Myhelper::FormValidator($rules, $request);
        if ($validate !== "no") {
            return $validate;
        }
        
        $gps_location = $request->gps_location;
        // Decode the encoded characters
        $gps_location = urldecode($gps_location);
        // Split the latitude and longitude
        list($lat, $lon) = explode('/', $gps_location);
        
        // Data to insert/update
        $data = [
            'bcid'             => self::generateBCAgentId(),
            'agentName'        => $request->agentName,
            'agentLastName'    => $request->agentLastName,
            'agentDob'         => $request->agentDob,
            'agentEmail'       => $request->agentEmail,
            'agentPhoneNumber' => $request->agentPhoneNumber,
            'agentAddress'     => $request->agentAddress ?? null,
            'agentCityName'    => $request->agentCityName,
            'agentShopName'    => $request->agentShopName,
            'agentState'       => $request->agentState ?? null,
            'agentPinCode'     => $request->agentPinCode ?? null,
            'userPan'          => $request->userPan,
            'status'           => 'pending',
            'kioskcode'        => $request->user_id,
            'lat'              => $lat,
            'lon'              => $lon,
            'updated_at'       => now(),
            
            // Additional fields
            'middlename'       => $request->middlename ?? null,
            'alternatenumber'  => $request->alternatenumber ?? null,
            'telephone'        => $request->telephone ?? null,
            'district'         => $request->district ?? null,
            'area'             => $request->area ?? null,
            'shopaddress'      => $request->shopaddress ?? null,
            'shopstate'        => $request->shopstate ?? null,
            'shopcity'         => $request->shopcity ?? null,
            'shopdistrict'     => $request->shopdistrict ?? null,
            'shoparea'         => $request->shoparea ?? null,
            'shoppincode'      => $request->shoppincode ?? null,
            'dmt'              => $request->has('dmt') ? 1 : 0,
            'aeps'             => $request->has('aeps') ? 1 : 0,
            'cardpin'          => $request->has('cardpin') ? 1 : 0,
            'accountopen'      => $request->has('accountopen') ? 1 : 0,
            'tposserialno'     => $request->tposserialno ?? null,
            'temail'           => $request->temail ?? null,
            'taddress'         => $request->taddress ?? null,
            'taddress1'        => $request->taddress1 ?? null,
            'tstate'           => $request->tstate ?? null,
            'tcity'            => $request->tcity ?? null,
            'tpincode'         => $request->tpincode ?? null,
            'agenttype'        => $request->agenttype ?? 1, // Default to Normal Agent
            'agentbcid'        => $request->agentbcid ?? null,
        ];
        
        // Check if a record with the same kioskcode already exists
        $existingRecord = DB::table('emitra_agents')
            ->where('kioskcode', $request->user_id)
            ->first();
            
        if ($existingRecord) {
            // Update existing record
            $response = DB::table('emitra_agents')
                ->where('kioskcode', $request->user_id)
                ->update($data);
                
            $message = "Agent Nsdl account information updated. Please wait for approval";
        } else {
            // Add created_at for new records
            $data['created_at'] = now();
            // Insert new record
            $response = DB::table('emitra_agents')->insert($data);
            $message = "Agent Nsdl account onboarding process completed. Please wait for approval";
        }
        
        if ($response) {
            return response([
                'statuscode' => "TXN",
                'message'    => $message
            ]);
        } else {
            return response([
                'statuscode' => "ERR",
                "message"    => "Something went wrong"
            ]);
        }
    }

    /**
     * Process NSDL account opening
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public static function processAccount(Request $request)
    {
        // Validate request data
        $rules = [
            'Customername' => 'required',
            'Email'        => 'required|email',
            'mobileNo'     => 'required',
            'panNo'        => 'required'
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Generate unique transaction ID
        $today2 = date('ymdHis');
        do {
            $partOne = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 2);    
            $partTwo = substr(str_shuffle("0123456789"), 0, 3);  
            $pref    = "R";
            $txnid   = $pref.$today2.$partOne.$partTwo;
        } while (Report::where("txnid", "=", $txnid)->first() instanceof Report);
        
        $provider = Provider::where('recharge1', 'nsdlpaymentbank')->first();
        
        // Create report record
        $insert = [
            'number'      => $request->mobileNo,
            'mobile'      => $request->mobileNo,
            'provider_id' => $provider->id,
            'api_id'      => $provider->api->id,
            'amount'      => 120,
            'txnid'       => $txnid,
            'payid'       => $txnid,
            'option1'     => $request->Customername,
            'option5'     => $request->Email,
            'option7'     => $request->panNo,
            'description' => $request->encData ?? '',
            'status'      => 'pending',
            'user_id'     => 0,
            'credit_by'   => 1,
            'rtype'       => 'main',
            'balance'     => "0",
            'trans_type'  => 'none',
            'product'     => 'nsdl',
            'create_time' => $request->mobileNo."-".date('ymdhis')
        ];

        $action = Report::create($insert);
        
        if ($action) {
            $checksumStringCheck = "pin|nomineeName|nomineeDob|relationship|add2|add1|nomineeState|nomineeCity|add3|dateofbirth|pincode|customerLastName|mobileNo|customername|email|partnerRefNumber|clientid|dpid|customerDematId|bcid|applicationdocketnumber|tradingaccountnumber|bcagentid|customerRefNumber|partnerpan|partnerid|channelid|partnerCallBackURL|income|middleNameOfMother|panNo|kycFlag|maritalStatus|houseOfFatherOrSpouse";

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
                "email": "'.$request->Email.'",
                "mobileNo": "'.$request->mobileNo.'",
                "maritalStatus": "",
                "income": "",
                "middleNameOfMother": "",
                "houseOfFatherOrSpouse": "",
                "kycFlag": "",
                "panNo": "'.$request->panNo.'",
                "channelid": "GpuqFObVfGrMfbtATuTY",
                "partnerid": "xZ5W1JJbNV",
                "applicationdocketnumber": "",
                "dpid": "",
                "clientid": "",
                "partnerpan": "'.$request->onboarded_pan.'",
                "tradingaccountnumber": "",
                "partnerRefNumber": "",
                "customerRefNumber": "",
                "customerDematId": "",
                "partnerCallBackURL": "https://rajpay.in/apply/nsdl/saving_account",
                "bcid": "3653",
                "bcagentid": "'.$request->onboarded_agent_id.'"
            }';

            $body = '{
                "nomineeDetails": {
                    "nomineeName": "",
                    "nomineeDob": "",
                    "relationship": "",
                    "add1": "",
                    "add2": "",
                    "add3": "",
                    "pin": "",
                    "nomineeState": "",
                    "nomineeCity": ""
                },
                "personalDetails": {
                    "customername": "",
                    "customerLastName": "",
                    "dateofbirth": "",
                    "pincode": "",
                    "email": "'.$request->Email.'",
                    "mobileNo": "'.$request->mobileNo.'"
                },
                "otherDeatils": {
                    "maritalStatus": "",
                    "income": "",
                    "middleNameOfMother": "",
                    "houseOfFatherOrSpouse": "",
                    "kycFlag": "",
                    "panNo": "'.$request->panNo.'"
                },
                "additionalParameters": {
                    "channelid": "GpuqFObVfGrMfbtATuTY",
                    "partnerid": "xZ5W1JJbNV",
                    "applicationdocketnumber": "",
                    "dpid": "",
                    "clientid": "",
                    "partnerpan": "'.$request->onboarded_pan.'",
                    "tradingaccountnumber": "",
                    "partnerRefNumber": "",
                    "customerRefNumber": "",
                    "customerDematId": "",
                    "partnerCallBackURL": "https://rajpay.in/apply/nsdl/saving_account",
                    "bcid": "3653",
                    "bcagentid": "'.$request->onboarded_agent_id.'"
                }
            }';
                                    
            $arraybody = json_decode($jsonbody, true);
            $checksumStringCheckArray = explode("|", $checksumStringCheck);
            $checksumString = "";
            
            foreach ($checksumStringCheckArray as $key => $value) {
                $checksumString .= $arraybody[$value];
            }
            
            $fkey = "UBBluRkIcEyhPpVEpokWUvrAnykxvweUDVeUgArMJKWBUSKUTVnzDNTHzmipOPcgacCvpNQzyHXoOCUGTYwyKybCIMKvPUsjnZxKgbTRcLoRPoNSKeoYNDiipMqFiJjl";
            $signcs = base64_encode(hash_hmac('sha512', $checksumString, $fkey, true));

            $key = "UBBluRkIcEyhPpVE";
            $iv = "UBBluRkIcEyhPpVE";
            $encrypted = openssl_encrypt($body, 'aes-128-cbc', $key, 0, $iv);
            
            $redirectUrl = "https://nsdljiffy.co.in/jarvisjiffyBroker/accountOpen?partnerid=xZ5W1JJbNV&signcs=".$signcs."&encryptedStringCustomer=".urlencode($encrypted);
            
            $action->url = $redirectUrl;
            $action->save();

            return response()->json([
                "statuscode" => "TXN", 
                "message" => $redirectUrl
            ]);
        } else {
            return response()->json([
                'statuscode' => "ERR", 
                "message" => "Task Failed, please try again"
            ]);
        }
    }

    /**
     * Get NSDL transaction reports
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public static function getTransactionReports(Request $request)
    {
        if(!$request->has("fromdate") || empty($request->fromdate) || $request->fromdate == NULL || $request->fromdate == "null" ){
            $request['fromdate'] = date("Y-m-d");
        }

        if(!$request->has("todate") || empty($request->todate) || $request->todate == NULL || $request->todate == "null" ){
            $request['todate'] = $request->fromdate;
        }

        $query = DB::table("reports")
            ->orderBy('id', 'desc')
            ->where('rtype', "main")
            ->where('option3', $request->agent)
            ->where('product', 'nsdl');

        if(!empty($request->searchtext)){
            $serachDatas = ['txnid', 'refno'];
            $query->where(function($q) use($request, $serachDatas){
                foreach ($serachDatas as $value) {
                    $q->orWhere($value, 'like', '%'.$request->searchtext.'%');
                }
            });
        }

        if(isset($request->status) && $request->status != '' && $request->status != null){
            $query->where('status', $request->status);
        }

        $query->whereBetween('created_at', [
            Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), 
            Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')
        ]);
        
        $selects = [
            'id', 'mobile', 'number', 'url', 'txnid', 'amount', 'profit', 'charge', 'tds', 'gst', 
            'payid', 'refno', 'balance', 'status', 'rtype', 'trans_type', 'user_id', 'credit_by', 
            'created_at', 'product', 'option1', 'option3', 'option2', 'option5', 'option4', 'option6', 
            'option7', 'option8', 'option10', 'description', 'remark'
        ];

        $exportData = $query->select($selects);  
        $count = intval($exportData->count());
        
        if(isset($request['length']) && $request->length != "-1"){
            $exportData->skip($request['start'])->take($request['length']);
        }

        $data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $exportData->get()
        );
        
        return response()->json($data);
    }

    /**
     * Get agent details by ID
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public static function getAgentDetails(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:emitra_agents,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        // Fetch agent details
        $data = DB::table('emitra_agents')->where('id', $request->id)->first();

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Agent not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $data], 200);
    }

    /**
     * Update agent details
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public static function updateAgent(Request $request)
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'id' => 'required|integer|exists:emitra_agents,id',
                'current_account' => 'nullable|string|max:255',
                'bcId' => 'nullable|string|max:255',
                'agentName' => 'required|string|max:255',
                'middlename' => 'nullable|string|max:255',
                'agentLastName' => 'required|string|max:255',
                'agentDob' => 'nullable|date',
                'agentEmail' => 'nullable|email|max:255',
                'agentShopName' => 'nullable|string|max:255',
                'agentAddress' => 'nullable|string|max:500',
                'agentCityName' => 'nullable|string|max:255',
                'agentState' => 'nullable|string|max:255',
                'agentPhoneNumber' => 'nullable|string|max:15',
                'userPan' => 'nullable|string|max:10',
                'agentPinCode' => 'nullable|string|max:10',
                'status' => 'nullable|in:pending,approved,failed,rejected',
                'user_id' => 'nullable|integer',
                'via' => 'nullable|string|max:255',
                'lat' => 'nullable|numeric',
                'lon' => 'nullable|numeric',
                'kioskcode' => 'nullable|string|max:255',
                'alternatenumber' => 'nullable|string|max:15',
                'telephone' => 'nullable|string|max:15',
                'district' => 'nullable|string|max:255',
                'area' => 'nullable|string|max:255',
                'shopaddress' => 'nullable|string|max:500',
                'shopstate' => 'nullable|string|max:255',
                'shopcity' => 'nullable|string|max:255',
                'shopdistrict' => 'nullable|string|max:255',
                'shoparea' => 'nullable|string|max:255',
                'shoppincode' => 'nullable|string|max:10',
                'dmt' => 'nullable|boolean',
                'aeps' => 'nullable|boolean',
                'cardpin' => 'nullable|boolean',
                'accountopen' => 'nullable|boolean',
                'agenttype' => 'nullable|in:1,2',
                'agentbcid' => 'nullable|string|max:255',
                'tposserialno' => 'nullable|string|max:255',
                'temail' => 'nullable|email|max:255',
                'taddress' => 'nullable|string|max:500',
                'taddress1' => 'nullable|string|max:500',
                'tstate' => 'nullable|string|max:255',
                'tcity' => 'nullable|string|max:255',
                'tpincode' => 'nullable|string|max:10',
            ]);

            // Remove empty values from the data
            $filteredData = array_filter($validatedData, function ($value) {
                return $value !== null;
            });

            // Update agent record
            $updated = DB::table('emitra_agents')
                ->where('id', $request->id)
                ->update(array_merge($filteredData, ['updated_at' => now()]));

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Agent updated successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'No changes were made'], 200);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate a unique BC Agent ID
     * 
     * @return string
     */
    protected static function generateBCAgentId()
    {
        // Get the last recorded BCID
        $lastAgent = DB::table('emitra_agents')->latest('id')->value('bcid');

        if ($lastAgent) {
            // Extract numeric part and increment
            $numericPart = (int) filter_var($lastAgent, FILTER_SANITIZE_NUMBER_INT);
            $newNumericPart = $numericPart + 1;
        } else {
            // Start from 41 if no previous ID exists
            $newNumericPart = 41;
        }

        // Keep generating until a unique ID is found
        do {
            $newId = 'ACH' . str_pad($newNumericPart, 4, '0', STR_PAD_LEFT);
            $exists = DB::table('emitra_agents')->where('bcid', $newId)->exists();
            if ($exists) {
                $newNumericPart++;
            }
        } while ($exists);

        return $newId;
    }
}