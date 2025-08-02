<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Provider;
use Carbon\Carbon;

class PanCardHelper
{
    /**
     * Process a PAN application request
     * 
     * @param Request $request The request containing the PAN application data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function applyForPan(Request $request)
    {
        $rules = [
            'lastname' => 'required',
            'description' => 'required',
            'title'    => 'required',
            'mobile'   => 'required',
            'email'    => 'required',
            "consent"  => "required",
            "option2"  => "required",
        ];
        
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $today2 = date('ymdHis');

        // Generate unique transaction ID
        do {
            $partOne = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 2);    
            $partTwo = substr(str_shuffle("0123456789"), 0, 3);  
            $pref    = "R";
            $request["txnid"] = $pref.$today2.$partOne.$partTwo;
        } while (Report::where("txnid", "=", $request->txnid)->first() instanceof Report);

        try {
            $provider = Provider::where('recharge1', 'pancard')->first();
            $insert = [
                'number'  => $request->mobile,
                'mobile'  => $request->mobile,
                'provider_id' => $provider->id,
                'api_id'  => $provider->api->id,
                'amount'  => 107,
                'txnid'   => $request->txnid,
                'payid'   => $request->txnid,
                'option1' => $request->option1,
                'option2' => $request->option2,
                'option4' => $request->firstname." ".$request->middlename." ".$request->lastname,
                'option5' => $request->email,
                'option6' => $request->category,
                'option7' => $request->pancard,
                "option8" => $request->description,
                'description' => $request->encData,
                'status'  => 'pending',
                'user_id'    => 1,
                'credit_by'  => 1,
                'rtype'      => 'main',
                'balance'    => "0",
                'trans_type' => 'none',
                'product'    => 'directpancard',
                'create_time'=> $request->mobile."-".date('ymdhis')
            ];
    
            $report = Report::create($insert);
            
            // Create applicant data for the form
            $formData = [
                "applicantDto" => [
                    "appliType" => $request->option1,
                    "category"  => $request->category,
                    "title"     => $request->title,
                    "lastName"  => $request->lastname,
                    "firstName" => $request->firstname,
                    "middleName"=> $request->middlename,  
                    "applnMode" => $request->description,
                ],
                "otherDetails"  => [
                    "phyPanIsReq" => "Y",
                ],
                "telOrMobNo" => $request->mobile, 
            ];
    
            if($request->category == "CR"){
                $formData["applicantDto"]["pan"] = $request->pancard;
            }
    
            $parameterData = [
                "reqEntityData" => [
                    "txnid"           => $request->txnid,
                    "branchCode"      => $request->SSOID,
                    "entityId"        => "AchariyaTechno",
                    "dscProvider"     => "Verasys CA 2014",
                    "dscSerialNumber" => "2B 32 60 77",
                    "dscExpiryDate"   => "4-3-24",
                    "returnUrl"       => url("apply/pan/".$request->type),
                    "formData"        => base64_encode(json_encode($formData)),
                    "reqTs"           => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes")),
                    "authKey"         => "AchariyaTechno",
                ],
                "signature" => ""
            ];
    
            if($request->category == "A"){
                $type = "F";
            } else {
                $type = "C";
                $parameterData["reqEntityData"]["reqType"] = "CR";
            }
            
            // Get signature from API
            $url = "https://admin.e-banker.in/api/nsdl/signature?type=".$type."&data=".base64_encode(json_encode($parameterData));
            $signature = \Myhelper::curl($url, "POST", "", [], "no");
            
            if($request->category == "A"){
                $data = substr(json_decode(json_encode($signature["response"]), true), 38);
            } else {
                $data = substr(json_decode(json_encode($signature["response"]), true), 43);
            }
            
            $query["req"] = $data;
            return response()->json([
                'statuscode' => "TXN", 
                "data" => $query["req"], 
                "panData" => $formData, 
                "requestData" => $data, 
                "signature" => $signature["response"]
            ]);
        } catch (\Exception $e) {
            return response()->json(['statuscode' => "ERR", "message" => "Something went wrong"]);
        }
    }

    /**
     * Check PAN application status
     * 
     * @param Request $request The request containing the transaction ID
     * @return \Illuminate\Http\JsonResponse
     */
    public static function checkPanStatus(Request $request)
    {
        $rules = [
            'txnid' => 'required'
        ];
        
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $today = date('YmdH');
        $partOne = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 2);    
        $partTwo = substr(str_shuffle("0123456789"), 0, 3);  
        $rpid = $today.$partOne.$partTwo;

        $parameterData = [
            "panReqEntityData" => [
                "txnid"           => $rpid,
                "ackNo"           => $request->txnid,
                "entityId"        => "AchariyaTechno",
                "dscProvider"     => "Verasys CA 2014",
                "dscSerialNumber" => "2B 32 60 77",
                "dscExpiryDate"   => "4-3-24",
                "returnUrl"       => url("callback/nsdlpan"),
                "reqTs"           => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes")),
                "authKey"         => "AchariyaTechno",
            ],
            "signature" => ""
        ];
        
        $url = "https://admin.e-banker.in/api/nsdl/signature?type=S&data=".base64_encode(json_encode($parameterData));
        $signature = \Myhelper::curl($url, "POST", "", [], "no");
        $data = substr(json_decode(json_encode($signature["response"]), true), 45);

        $url = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/PanStatusReq";
        $response = \Myhelper::curl($url, "POST", $data, [
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 9a6f7632-b4ea-78f1-c393-12a31b12316a"
        ], "no");

        $pandata = json_decode($response["response"]);

        if(isset($pandata->panStatus)){
            return response()->json(['statuscode' => "TXN", "message" => $pandata->panStatus]);
        } else {
            return response()->json([
                'statuscode' => "ERR", 
                "message" => isset($pandata->error) ? json_encode($pandata->error) : "Something went wrong"
            ]);
        }
    }

    /**
     * Check transaction status
     * 
     * @param Request $request The request containing the transaction ID
     * @return \Illuminate\Http\JsonResponse
     */
    public static function checkTransactionStatus(Request $request)
    {
        $rules = [
            'txnid' => 'required'
        ];
        
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $parameterData = [
            "txnReqEntityData" => [
                "request_type"    => "T",
                "txn_id"          => $request->txnid,
                "unique_txn_id"   => $request->txnid,
                "date"            => "",
                "entity_Id"       => "AchariyaTechno",
                "dscProvider"     => "Verasys CA 2014",
                "dscSerialNumber" => "2B 32 60 77",
                "dscExpiryDate"   => "4-3-24",
                "returnUrl"       => url("callback/nsdlpan"),
                "reqTs"           => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes")),
                "authKey"         => "AchariyaTechno",
            ],
            "signature" => ""
        ];

        $url = "https://admin.e-banker.in/api/nsdl/signature?type=T&data=".base64_encode(json_encode($parameterData));
        $signature = \Myhelper::curl($url, "POST", "", [], "no");
        $data = substr(json_decode(json_encode($signature["response"]), true), 52);

        $url = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/checkTransactionStatus";
        $response = \Myhelper::curl($url, "POST", $data, [
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 9a6f7632-b4ea-78f1-c393-12a31b12316a"
        ], "no");

        $pandata = json_decode($response["response"]);
        
        if(isset($pandata->status) && $pandata->status == "success"){
            return response()->json(['statuscode' => "TXN", "message" => "Your acknowledgement number is ".$pandata->ack_No]);
        } elseif(isset($pandata->error) && $pandata->error != null){
            return response()->json([
                'statuscode' => "ERR", 
                "message" => isset($pandata->error) ? json_encode($pandata->error) : "Something went wrong"
            ]);
        } else {
            return response()->json([
                'statuscode' => "ERR", 
                "message" => isset($pandata->errordesc) ? $pandata->errordesc : "Something went wrong"
            ]);
        }
    }

    /**
     * Get PAN card application reports
     * 
     * @param Request $request The request containing filter parameters
     * @return string JSON encoded data
     */
    public static function getPanReports(Request $request)
    {
        if(!$request->has("fromdate") || empty($request->fromdate) || $request->fromdate == NULL || $request->fromdate == "null" ){
            $request['fromdate'] = date("Y-m-d");
        }

        if(!$request->has("todate") || empty($request->todate) || $request->todate == NULL || $request->todate == "null" ){
            $request['todate'] = $request->fromdate;
        }

        $query = \DB::table("reports")
            ->orderBy('id', 'desc')
            ->where('rtype', "main")
            ->where('option3', $request->agent)
            ->where('product', 'directpancard');

        if(!empty($request->searchtext)){
            $searchFields = ['txnid', 'refno'];
            $query->where(function($q) use($request, $searchFields){
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', '%'.$request->searchtext.'%');
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
            'id', 'mobile', 'number', 'txnid', 'amount', 'profit', 'charge', 'tds', 'gst', 
            'payid', 'refno', 'balance', 'status', 'rtype', 'trans_type', 'user_id', 
            'credit_by', 'created_at', 'product', 'option1', 'option3', 'option2', 
            'option5', 'option4', 'option6', 'option7', 'option8', 'option10', 
            'description', 'remark'
        ];

        $exportData = $query->select($selects);  
        $count = intval($exportData->count());

        if(isset($request['length']) && $request->length != "-1"){
            $exportData->skip($request['start'])->take($request['length']);
        }

        return json_encode([
            "draw"            => intval($request['draw']),
            "recordsTotal"    => $count,
            "recordsFiltered" => $count,
            "data"            => $exportData->get()
        ]);
    }
}