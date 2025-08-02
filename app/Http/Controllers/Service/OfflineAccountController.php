<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Report;
use App\Model\Provider;
use App\Model\NpsAccount;
use App\Model\Company;
use App\Model\Companydata;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class OfflineAccountController extends Controller
{
    public function apply(Request $post, $product, $type)
    {
        // dd($post->get('form_source'));
        $type = strtolower($type);
        $data["type"]    = $type;
        $data["product"] = $product;
        $data['company'] = Company::where('website', $_SERVER['HTTP_HOST'])->first();

        if ($data['company']) {
            $data['companydata'] = Companydata::where('company_id', $data['company']->id)->first();
        }

        switch ($product) {
            // case 'nsdl':
            //     return view('apply.nsdlaccount')->with($data);
            //     break;

            case 'nps':
                return view('apply.nps')->with($data);
                break;

            default:
                if ($post->has("encreqdetails")) {
                    \DB::table('microlog')->insert(["product" => "Nsdlbank", 'response' => json_encode($post->all()), 'created_at' => date('Y-m-d H:i:s')]);
                }

                if ($post->has("debug")) {
                    $key = "UBBluRkIcEyhPpVE";
                    $iv = "UBBluRkIcEyhPpVE";
                    $textToDecrypt = $post->encreqdetails;

                    $decryptedData = openssl_decrypt($textToDecrypt, 'aes-128-cbc', $key, 0, $iv);
                    $dataArray = json_decode($decryptedData, true);
                    // dd($dataArray, $dataArray['response']);

                    $report = Report::where('option5', $post->email)->where("status", "pending")->first();

                    if ($report) {
                        $post["txnid"] = $report->txnid;
                        if ($dataArray['response'] == "SUCCESS") {
                        } elseif ($dataArray['response'] == "FAILED") {
                        } else {
                        }
                    } else {
                    }
                }

                if ($post->has("txnStatus")) {
                    $data["txnid"] = $post->txnid;
                    $report = Report::where('txnid', $post->txnid)->where("status", "pending")->first();

                    if ($report) {
                        $post["encData"] = $report->description;
                        if ($post->txnStatus == "1") {
                            $data['status'] = "success";
                            $data['refno']  = $post->ackNo;
                            $data['remark'] = $post->ackNo;
                        } else {
                            $data['status'] = "pending";
                            $data['refno']  = "Transaction under process";
                            $data['remark'] = isset($post->errorMsg) ? $post->errorMsg : "Check status after 1 hour";
                        }

                        if (isset($data['status'])) {
                            if ($data["status"] == "failed") {
                                Report::where('txnid', $post->txnid)->update([
                                    "status" => $data['status'],
                                    "refno"  => $data['refno'],
                                    "remark" => $data['remark']
                                ]);
                            } else {
                                Report::where('txnid', $post->txnid)->update([
                                    "status" => $data['status'],
                                    "refno"  => $data['refno'],
                                    "remark" => $data['remark']
                                ]);
                            }
                        }
                    } else {
                        return redirect(url('apply/pancard/new'));
                    }
                }

                $data["encData"] = $post->encData;
                $dataencData = "toBeDecrypt=" . $post->encData;

                // dd($dataencData);
                $header = [
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ];

                $url  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption";
                $curl = \Myhelper::curl($url, "POST", $dataencData, $header, "no");

                if ($curl["response"] == "") {

                    $userIp = request()->ip();


                    // Fetch the last created record by this IP address
                    $data = DB::table('nsdl_session')
                        ->where('ip_address', $userIp)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    // dd($data);   

                    // Convert JSON fields to arrays
                    // if ($data) {
                    //     $data = (array) $data;
                    //     $data['company'] = json_decode($data['company'], true);
                    //     $data['companydata'] = json_decode($data['companydata'], true);
                    //     $data['KIOSKDATA'] = json_decode($data['KIOSKDATA'], true);
                    //     $data['bankagentid'] = json_decode($data['bankagentid'], true);
                    //     $data['username'] = json_decode($data['username'], true);
                    //     $data['states'] = json_decode($data['states'], true);
                    //     $data['KIOSKCODEJ'] = isset($data['KIOSKCODEJ']) ? json_decode($data['KIOSKCODEJ'], true) : null;
                    // }
                    if ($data) {
                        $data = (array) $data;

                        $data['company']      = json_decode($data['company'] ?? '[]', true);
                        $data['companydata']  = json_decode($data['companydata'] ?? '[]', true);
                        $data['KIOSKDATA']    = json_decode($data['KIOSKDATA'] ?? '[]', true);
                        $data['bankagentid']  = json_decode($data['bankagentid'] ?? '[]', true);
                        $data['username']     = json_decode($data['username'] ?? '[]', true);
                        $data['states']       = json_decode($data['states'] ?? '[]', true);
                        $data['KIOSKCODEJ']   = isset($data['KIOSKCODEJ']) ? json_decode($data['KIOSKCODEJ'], true) : null;
                    } else {
                        // Create empty default values if no session found
                        $data = [
                            'company'      => [],
                            'companydata'  => [],
                            'KIOSKDATA'    => [],
                            'bankagentid'  => [],
                            'username'     => [],
                            'states'       => [],
                            'KIOSKCODEJ'   => null,
                        ];
                    }

                    // Encryption keys
                    $key = config('app.nsdl_key', 'UBBluRkIcEyhPpVE');
                    $iv = config('app.nsdl_iv', 'UBBluRkIcEyhPpVE');

                    // Get the encrypted response data
                    $encryptedDetails = $post->input('encreqdetails');

                    // Decrypt the response
                    $decryptedDetails = null;
                    $response = null;
                    // dd($encryptedDetails);
                    if ($encryptedDetails) {
                        try {
                            $decryptedDetails = openssl_decrypt(
                                $encryptedDetails,
                                'aes-128-cbc',
                                $key,
                                0,
                                $iv
                            );

                            // Parse decrypted JSON
                            $response = json_decode($decryptedDetails, true);
                            $responseData = $response['response'] ?? null;
                        } catch (\Exception $e) {
                            Log::error('NSDL Callback Decryption Error', [
                                'error' => $e->getMessage()
                            ]);

                            return view('apply.nsdlaccount', [
                                'data' => $data,
                                'response' => 'Decryption failed'
                            ]);
                        }
                    }
                    $data['responseData'] = $responseData ?? null;

                    session()->flash('responseData', $data['responseData']);
                    // Render the view with both $data and $responseData
                    // return view('apply.nsdlaccount', [
                    //     'data' => $data,
                    //     'response' => $responseData
                    // ]);

                    DB::table('reports')
                        ->where('option3', $data['KIOSKCODEJ'])
                        ->orderBy('created_at', 'desc')
                        ->limit(1)
                        ->update(['remark' => $data['responseData']]);

                    //     if (!empty($data['KIOSKCODEJ'])) {
                    //     DB::table('reports')
                    //     ->where('option3', $data['KIOSKCODEJ'])
                    //     ->orderBy('created_at', 'desc')
                    //     ->limit(1)
                    //     ->update(['remark' => $data['responseData']]);
                    //    } else {
                    //    \Log::warning('KIOSKCODEJ missing during report update', ['responseData' => $data['responseData'] ?? null]);
                    //    }
                    return view('apply.nsdlaccount')->with($data);
                } else {

                    $charactersencData = json_decode($curl["response"]);
                    $SSOID        = $charactersencData->SSOID;
                    $SERVICEID    = $charactersencData->SERVICEID;
                    $EMSESSIONID  = $charactersencData->EMSESSIONID;
                    $KIOSKCODE    = $charactersencData->KIOSKCODE;
                    $OLDKIOSKCODE = $charactersencData->OLDKIOSKCODE;
                    $DISTRICTCD   = $charactersencData->DISTRICTCD;
                    $TEHSILCD     = $charactersencData->TEHSILCD;
                    $RETURNURL    = $charactersencData->RETURNURL;
                    $EMITRATIMESTAMP = $charactersencData->EMITRATIMESTAMP;
                    $SSOTOKEN = $charactersencData->SSOTOKEN;
                    $CHECKSUM = $charactersencData->CHECKSUM;

                    $data['SSOID'] = $SSOID;
                    $data['SERVICEID'] = $SERVICEID;
                    $data['SSOTOKEN'] = $SSOTOKEN;

                    if ($RETURNURL == "") {
                        $RETURNURL = "https://sso.rajasthan.gov.in/pos";
                    }

                    $data['RETURNURL'] = $RETURNURL;

                    $urlverify = "http://sso.rajasthan.gov.in:8888/SSOREST/GetTokenDetail/" . $SSOTOKEN;
                    $curlverify = \Myhelper::curl($urlverify, "GET", "", [], "no", "none", "none", "8888");

                    if ($curlverify["response"] == "") {
                        dd("2", $curlverify["error"], $curlverify["response"]);
                    }

                    $datagetKioskDetailsJSON = "MERCHANTCODE=MODISH2022&SSOID=" . $SSOID;
                    $kioskurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/getKioskDetailsJSON";
                    $kioskcurl = \Myhelper::curl($kioskurl, "POST", $datagetKioskDetailsJSON, $header, "no");

                    if ($kioskcurl["response"] == "") {
                        dd("3", $kioskcurl["error"], $kioskcurl["response"]);
                    } else {
                        $charactersgetKioskDetailsJSON = json_decode($kioskcurl["response"]);
                        $REQUESTTIMESTAMPJ  = $charactersgetKioskDetailsJSON->REQUESTTIMESTAMP;
                        $REQUESTSTATUSCODEJ = $charactersgetKioskDetailsJSON->REQUESTSTATUSCODE;
                        $MSGJ   = $charactersgetKioskDetailsJSON->MSG;
                        $SSOIDJ = $charactersgetKioskDetailsJSON->SSOID;
                        $MERCHANTCODEJ = $charactersgetKioskDetailsJSON->MERCHANTCODE;
                        $KIOSKCODEJ    = $charactersgetKioskDetailsJSON->KIOSKCODE;
                        $OLDKIOSKCODEJ = $charactersgetKioskDetailsJSON->OLDKIOSKCODE;
                        $KIOSKNAMEJ    = $charactersgetKioskDetailsJSON->KIOSKNAME;
                        $ENTITYTYPEJ   = $charactersgetKioskDetailsJSON->ENTITYTYPE;
                        $DISTRICTJ     = $charactersgetKioskDetailsJSON->DISTRICT;
                        $DISTRICTCDJ   = $charactersgetKioskDetailsJSON->DISTRICTCD;
                        $TEHSILJ       = $charactersgetKioskDetailsJSON->TEHSIL;
                        $TEHSILCDJ     = $charactersgetKioskDetailsJSON->TEHSILCD;
                        $VILLAGEJ      = $charactersgetKioskDetailsJSON->VILLAGE;
                        $VILLAGECDJ    = $charactersgetKioskDetailsJSON->VILLAGECD;
                        $WARDJ         = $charactersgetKioskDetailsJSON->WARD;
                        $WARDCDJ       = $charactersgetKioskDetailsJSON->WARDCD;
                        $PINCODEJ      = $charactersgetKioskDetailsJSON->PINCODE;
                        $MOBILEJ       = $charactersgetKioskDetailsJSON->MOBILE;
                        $EMAILJ        = $charactersgetKioskDetailsJSON->EMAIL;
                        $KIOSK_ADMINJ  = $charactersgetKioskDetailsJSON->KIOSK_ADMIN;
                        $LSPNAMEJ = $charactersgetKioskDetailsJSON->LSPNAME;
                        $LSPCODEJ = $charactersgetKioskDetailsJSON->LSPCODE;
                        $EMITRATIMESTAMPJ = $charactersgetKioskDetailsJSON->EMITRATIMESTAMP;

                        $data['KIOSKNAMEJ'] = $KIOSKNAMEJ;
                        $data['KIOSKCODEJ'] = $KIOSKCODEJ;
                        $data['KIOSKDATA']  = $kioskcurl["response"];
                    }
                }

                if ($product == "nsdl") {
                    // Extract kiosk code from data
                    $kioskCode = $data['KIOSKCODEJ'] ?? null;



                    // Fetch agent details correctly using kioskcode
                    $data['bankagentid'] = DB::table('emitra_agents')->where('kioskcode', $kioskCode)->first();

                    if (!$data['bankagentid']) {
                        // If no data exists, set bankagent to false
                        $data['bankagent'] = false;
                        $data['agent_onboarded'] = false;
                    } elseif ($data['bankagentid']->status == 'pending' || $data['bankagentid']->status == 'rejected') {
                        $data['agent_onboarded'] = true;
                        if ($data['bankagentid']->status == 'rejected') {
                            $data['agent_onboarded'] = false;
                        }
                        $data['bankagent'] = false;
                    } else {
                        $data['bankagent'] = true;
                        // agent_onboard = DB::table('emitra_agents')->where(['kiosk_code' , $kioskCode] , status )

                    }



                    // Extract first and last name from KIOSKNAMEJ
                    $data['username'] = isset($data['KIOSKNAMEJ']) ? explode(" ", $data['KIOSKNAMEJ'], 2) : [];
                    $data['firstname'] = $data['username'][0] ?? '';
                    $data['lastname'] = $data['username'][1] ?? '';

                    // Fetch states
                    $data['states'] = DB::table('circles')->orderBy('state', 'asc')->get();

                    $data['ip_address'] = request()->ip(); // Get the user's IP address
                    // session(['nsdl_data' => $data]);



                    DB::table('nsdl_session')->insert([
                        'type' => $data['type'] ?? null,
                        'product' => $data['product'] ?? null,
                        'company' => json_encode($data['company'] ?? []),
                        'companydata' => json_encode($data['companydata'] ?? []),
                        'encData' => $data['encData'] ?? null,
                        'SSOID' => $data['SSOID'] ?? null,
                        'SERVICEID' => $data['SERVICEID'] ?? null,
                        'SSOTOKEN' => $data['SSOTOKEN'] ?? null,
                        'RETURNURL' => $data['RETURNURL'] ?? null,
                        'KIOSKNAMEJ' => $data['KIOSKNAMEJ'] ?? null,
                        'KIOSKCODEJ' => $data['KIOSKCODEJ'] ?? null,
                        'KIOSKDATA' => json_encode($data['KIOSKDATA'] ?? []),
                        'bankagentid' => json_encode($data['bankagentid'] ?? []),
                        'bankagent' => $data['bankagent'] ?? false,
                        'username' => json_encode($data['username'] ?? []),
                        'firstname' => $data['firstname'] ?? null,
                        'lastname' => $data['lastname'] ?? null,
                        'states' => json_encode($data['states'] ?? []),
                        'ip_address' => request()->ip(), // Get User IP
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    return view('apply.nsdlaccount')->with($data);
                } else {
                    return view('apply.nsdlpancard')->with($data);
                }
                break;
        }
    }
    /**
     * Fetch the approvals data for datatable
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Fetch pending approvals for DataTables
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchApprovals(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search')['value'];

            // Base query
            $query = DB::table('emitra_agents')->select('*');

            // Apply search if provided
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('kioskcode', 'like', "%{$search}%")
                        ->orWhere('agentName', 'like', "%{$search}%")
                        ->orWhere('agentLastName', 'like', "%{$search}%")
                        ->orWhere('agentEmail', 'like', "%{$search}%")
                        ->orWhere('agentPhoneNumber', 'like', "%{$search}%")
                        ->orWhere('agentShopName', 'like', "%{$search}%");
                });
            }

            // Count total records
            $totalRecords = $query->count();

            // Get paginated data
            $data = $query->skip($start)
                ->take($length)
                ->get();

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * View agent details
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * Update approval status
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'id' => 'required|exists:emitra_agents,id',
                'status' => 'required|in:approved,rejected'
            ]);

            // Update status using Query Builder
            $updated = DB::table('emitra_agents')
                ->where('id', $request->id)
                ->update(['status' => $request->status]);

            // Check if the update was successful
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No changes made'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function pending_approvals_list()
    {
        try {
            return view('pending_approvals');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function panApplyProcess(Request $post)
    {
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $newTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes"));
        $today   = date('YmdHisU');
        $today2  = date('ymdHis');
        $mtime   = substr($today, 0, -7);

        do {
            $partOne = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 2);
            $partTwo = substr(str_shuffle("0123456789"), 0, 3);
            $pref    = "R";
            $post["txnid"] = $pref . $today2 . $partOne . $partTwo;
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        $checkSumData = array(
            "SSOID"     => $post->SSOID,
            "REQUESTID" => $post->txnid,
            "REQTIMESTAMP" => $mtime,
            "SSOTOKEN"  => $post->SSOTOKEN
        );
        $checkSumData_query = "toBeCheckSumString=" . json_encode($checkSumData);

        $header = [
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ];

        $checkSumurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum";
        $checkSumcurl = \Myhelper::curl($checkSumurl, "POST", $checkSumData_query, $header, "no");

        if ($checkSumcurl["error"] || $checkSumcurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        $reven = "3206-107.00|3207-10.00";
        $databacktobackenc = array(
            "MERCHANTCODE" => "MODISH2022",
            "REQTIMESTAMP" => $mtime,
            "SUBSERVICEID" => "",
            "REVENUEHEAD"  => $reven,
            "CONSUMERKEY"  => $post->txnid,
            "OFFICECODE"   => "MODISHHQ",
            "CONSUMERNAME" => $post->firstname . " " . $post->middlename . " " . $post->lastname,
            "COMMTYPE"     => "3",
            "SSOTOKEN"     => $post->SSOTOKEN,
            "REQUESTID"    => $post->txnid,
            "SSOID"        => $post->SSOID,
            "SERVICEID"    => $post->SERVICEID,
            "CHECKSUM"     => $checkSumcurl["response"],
        );

        $dataForEncryption = "toBeEncrypt=" . json_encode($databacktobackenc);

        $encurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption";
        $enccurl = \Myhelper::curl($encurl, "POST", $dataForEncryption, $header, "no");

        if ($enccurl["error"] || $enccurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => $enccurl["error"] . "/" . $enccurl["response"]]);
        }

        $backTobackEncryption = "encData=" . $enccurl["response"];

        $backencurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/backtobackTransactionWithEncryptionA";
        $backenccurl = \Myhelper::curl($backencurl, "POST", $backTobackEncryption, $header, "no");

        if ($backenccurl["error"] || $backenccurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        $datchecksumencbacktobackTransaction = "toBeDecrypt=" . $backenccurl["response"];

        $backencdecurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption";
        $backencdeccurl = \Myhelper::curl($backencdecurl, "POST", $datchecksumencbacktobackTransaction, $header, "no");

        //dd(json_encode($databacktobackenc), $backencdeccurl);
        if ($backencdeccurl["error"] || $backencdeccurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        $charactersencDataTransaction = json_decode($backencdeccurl["response"]);
        $REQUESTID             = $charactersencDataTransaction->REQUESTID;
        $TRANSACTIONSTATUSCODE = $charactersencDataTransaction->TRANSACTIONSTATUSCODE;
        $RECEIPTNO             = $charactersencDataTransaction->RECEIPTNO;

        $TRANSACTIONID     = $charactersencDataTransaction->TRANSACTIONID;
        $TRANSAMT          = $charactersencDataTransaction->TRANSAMT;
        $REMAININGWALLET   = $charactersencDataTransaction->REMAININGWALLET;
        $MSG               = $charactersencDataTransaction->MSG;
        $TRANSACTIONSTATUS = $charactersencDataTransaction->TRANSACTIONSTATUS;

        $CHECKSUMf = $charactersencDataTransaction->CHECKSUM;

        if ($TRANSACTIONSTATUSCODE != "200" || $TRANSACTIONSTATUS != "SUCCESS") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        try {
            $provider = Provider::where('recharge1', 'pancard')->first();
            $insert = [
                'number'  => $post->mobile,
                'mobile'  => $post->mobile,
                'provider_id' => $provider->id,
                'api_id'  => $provider->api->id,
                'amount'  => 107,
                'txnid'   => $post->txnid,
                'payid'   => $TRANSACTIONID,
                'option1' => $post->option1,
                'option2' => $post->option2,
                'option3' => $post->KIOSKCODEJ,
                'option4' => $post->firstname . " " . $post->middlename . " " . $post->lastname,
                'option5' => $post->email,
                'option6' => $post->category,
                'option7' => $post->pancard,
                "option8" => $post->description,
                "option9" => $post->SSOTOKEN,
                "option10" => $post->KIOSKDATA,
                'description' => $post->encData,
                'status'  => 'pending',
                'user_id'    => 1,
                'credit_by'  => 1,
                'rtype'      => 'main',
                'balance'    => "0",
                'trans_type' => 'none',
                'product'    => 'directpancard',
                // 'create_time'=> $post->mobile."-".date('ymdhis')
            ];

            $report = Report::create($insert);
            $formData = array(
                "applicantDto" => array(
                    "appliType" => $post->option1,
                    "category"  => $post->category,
                    "title"     => $post->title,
                    "lastName"  => $post->lastname,
                    "firstName" => $post->firstname,
                    "middleName" => $post->middlename,
                    "applnMode" => $post->description,
                ),
                "otherDetails"  => array(
                    "phyPanIsReq" => "Y",
                ),
                "telOrMobNo" => $post->mobile,
            );

            if ($post->category == "CR") {
                $formData["applicantDto"]["pan"] = $post->pancard;
            }

            $parameterData = array(
                "reqEntityData" => array(
                    "txnid"           => $post->txnid,
                    "branchCode"      => $post->SSOID,
                    "entityId"        => "AchariyaTechno",
                    "dscProvider"     => "Verasys CA 2014",
                    "dscSerialNumber" => "2B 32 60 77",
                    "dscExpiryDate"   => "4-3-24",
                    "returnUrl" => url("apply/pan/" . $post->type),
                    "formData"  => base64_encode(json_encode($formData)),
                    "reqTs"     => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes")),
                    "authKey"   => "AchariyaTechno",
                ),
                "signature" => ""
            );

            if ($post->category == "A") {
                $type = "F";
            } else {
                $type = "C";
                $parameterData["reqEntityData"]["reqType"] = "CR";
            }





            $base64Request = base64_encode(json_encode($parameterData));


            $certPath = public_path('YESPAL.pfx');
            $certPassword = env('CERT_PASSWORD', '123456789');
            $jarPath = public_path('Assissted_Signing_v3.jar');
            $alias = 'yespal singh'; // Certificate alias
            $flag = $type;


            $command = "java -jar \"$jarPath\" \"$base64Request\" \"$certPath\" \"$certPassword\" \"$alias\" \"$flag\" 2>&1";


            $signedRequest = shell_exec($command);

            $startPos = strpos($signedRequest, '{');
            $endPos = strrpos($signedRequest, '}');


            if ($post->category == "A") {

                $fullResponse = substr($signedRequest, $startPos, $endPos - $startPos + 1);
            } else {

                $fullResponse = substr($signedRequest, $startPos, $endPos - $startPos + 1);
            }
            $data = $fullResponse = substr($signedRequest, $startPos, $endPos - $startPos + 1);
            $query["req"] = $fullResponse;
            return response()->json(['statuscode' => "TXN", "data" => $query["req"], "panData" => $formData, "requestData" => $data, "signature" => $signedRequest]);
        } catch (\Exception $e) {

            dd($e);
            $checkSumData = array(
                "MERCHANTCODE" => "MODISH2022",
                "REQUESTID"    => $post->txnid,
                "SSOTOKEN"     => "0"
            );
            $checkSumData_query = "toBeCheckSumString=" . json_encode($checkSumData);

            $header = [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ];

            $checkSumurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum";
            $checkSumcurl = \Myhelper::curl($checkSumurl, "POST", $checkSumData_query, $header, "no");

            if ($checkSumcurl["response"] != "") {
                $failedData = array(
                    "MERCHANTCODE" => "MODISH2022",
                    "SSOTOKEN"     => "0",
                    "REQUESTID"    => $post->txnid,
                    "EMITRATOKEN"  => $post->txnid,
                    "CHECKSUM"     => $checkSumcurl["response"],
                    "ENTITYTYPEID" => "2",
                    "CANCELREMARK" => "Timeout"
                );

                $header = [
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ];

                $dataForEncryption = "toBeEncrypt=" . json_encode($failedData);

                $encurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption";
                $enccurl = \Myhelper::curl($encurl, "POST", $dataForEncryption, $header, "no");

                $backToCancleEncryption = "encData=" . $enccurl["response"];
                $checkSumurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/backendTransCancelByDepartmentWithEncryption";
                $checkSumcurl = \Myhelper::curl($checkSumurl, "POST", $backToCancleEncryption, $header, "no");

                $dataencData = "toBeDecrypt=" . $checkSumcurl["response"];
                $header = [
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ];

                $url  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption";
                $curl = \Myhelper::curl($url, "POST", $dataencData, $header, "no");

                $cancleData = json_decode($curl["response"]);

                \DB::table('log_500')->insert([
                    'line' => json_encode($checkSumData) . "/" . json_encode($failedData),
                    'file' => $e->getFile(),
                    'log'  => $curl["response"] . $e->getMessage(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                return response()->json(['statuscode' => "ERR", "message" => "Something went wrong"]);
            }
        }
    }

    public function panStatusProcess(Request $post)
    {
        $rules = array(
            'txnid' => 'required'
        );

        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $today = date('YmdH');
        $partOne =  substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 2);
        $partTwo =  substr(str_shuffle("0123456789"), 0, 3);
        $rpid    = $today . $partOne . $partTwo;

        $parameterData = array(
            "panReqEntityData" => array(
                //"txnid"           => "230574006443",
                "txnid"           => $rpid,
                "ackNo"           => $post->txnid,
                "entityId"        => "AchariyaTechno",
                "dscProvider"     => "Verasys CA 2014",
                "dscSerialNumber" => "2B 32 60 77",
                "dscExpiryDate"   => "4-3-24",
                "returnUrl" => url("callback/nsdlpan"),
                "reqTs"     => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes")),
                "authKey"   => "AchariyaTechno",
            ),
            "signature" => ""
        );

        // $url = "https://admin.e-banker.in/api/nsdl/signature?type=S&data=".base64_encode(json_encode($parameterData));
        // $signature = \Myhelper::curl($url, "POST", "", [], "no");

        $base64Request = base64_encode(json_encode($parameterData));


        $certPath = public_path('YESPAL.pfx');
        $certPassword = env('CERT_PASSWORD', '123456789');
        $jarPath = public_path('Assissted_Signing_v3.jar');
        $alias = 'yespal singh'; // Certificate alias
        $flag = 'S';


        $command = "java -jar \"$jarPath\" \"$base64Request\" \"$certPath\" \"$certPassword\" \"$alias\" \"$flag\" 2>&1";


        $signedRequest = shell_exec($command);

        $startPos = strpos($signedRequest, '{');
        $endPos = strrpos($signedRequest, '}');
        $fullResponse = substr($signedRequest, $startPos, $endPos - $startPos + 1);
        // dd($fullResponse);
        $data = $fullResponse;

        $url = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/PanStatusReq";
        $response = \Myhelper::curl($url, "POST", $data, array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 9a6f7632-b4ea-78f1-c393-12a31b12316a"
        ), "no");

        $pandata = json_decode($response["response"]);

        if (isset($pandata->panStatus)) {
            return response()->json(['statuscode' => "TXN", "message" => $pandata->panStatus]);
        } else {
            return response()->json(['statuscode' => "ERR", "message" => isset($pandata->error) ? json_encode($pandata->error) : "Something went wrong"]);
        }
    }

    public function txnStatusProcess(Request $post)
    {
        $rules = array(
            'txnid' => 'required'
        );

        $validator = \Validator::make($post->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $parameterData = array(
            "txnReqEntityData" => array(
                "request_type"    => "T",
                "txn_id"          => $post->txnid,
                "unique_txn_id"   => $post->txnid,
                "date"            => "",
                "entity_Id"       => "AchariyaTechno",
                "dscProvider"     => "Verasys CA 2014",
                "dscSerialNumber" => "2B 32 60 77",
                "dscExpiryDate"   => "4-3-24",
                "returnUrl" => url("callback/nsdlpan"),
                "reqTs"     => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes")),
                "authKey"   => "AchariyaTechno",
            ),
            "signature" => ""
        );

        // $url = "https://admin.e-banker.in/api/nsdl/signature?type=T&data=".base64_encode(json_encode($parameterData));
        // $signature = \Myhelper::curl($url, "POST", "", [], "no");
        $base64Request = base64_encode(json_encode($parameterData));


        $certPath = public_path('YESPAL.pfx');
        $certPassword = env('CERT_PASSWORD', '123456789');
        $jarPath = public_path('Assissted_Signing_v3.jar');
        $alias = 'yespal singh'; // Certificate alias
        $flag = 'T';


        $command = "java -jar \"$jarPath\" \"$base64Request\" \"$certPath\" \"$certPassword\" \"$alias\" \"$flag\" 2>&1";


        $signedRequest = shell_exec($command);

        $startPos = strpos($signedRequest, '{');
        $endPos = strrpos($signedRequest, '}');
        $fullResponse = substr($signedRequest, $startPos, $endPos - $startPos + 1);
        // dd($fullResponse);
        $data = $fullResponse;
        // $data      = substr(json_decode(json_encode($signature["response"]), true), 52);

        $url = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/checkTransactionStatus";
        $response = \Myhelper::curl($url, "POST", $data, array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 9a6f7632-b4ea-78f1-c393-12a31b12316a"
        ), "no");

        $pandata = json_decode($response["response"]);
        if (isset($pandata->status) && $pandata->status == "success") {
            return response()->json(['statuscode' => "TXN", "message" => "Your acknowledgement number is " . $pandata->ack_No]);
        } elseif (isset($pandata->error) && $pandata->error != null) {
            return response()->json(['statuscode' => "ERR", "message" => isset($pandata->error) ? json_encode($pandata->error) : "Something went wrong"]);
        } else {
            return response()->json(['statuscode' => "ERR", "message" => isset($pandata->errordesc) ? $pandata->errordesc : "Something went wrong"]);
        }
    }

    public function panreport(Request $request)
    {
        if (!$request->has("fromdate") || empty($request->fromdate) || $request->fromdate == NULL || $request->fromdate == "null") {
            $request['fromdate'] = date("Y-m-d");
        }

        if (!$request->has("todate") || empty($request->todate) || $request->todate == NULL || $request->todate == "null") {
            $request['todate'] = $request->fromdate;
        }

        $query  = \DB::table("reports")
            ->orderBy('id', 'desc')
            ->where('rtype', "main")
            ->where('option3', $request->agent)
            ->where('product', 'directpancard');

        if (!empty($request->searchtext)) {
            $serachDatas = ['txnid', 'refno'];
            $query->where(function ($q) use ($request, $serachDatas) {
                foreach ($serachDatas as $value) {
                    $q->orWhere($value, 'like', '%' . $request->searchtext . '%');
                }
            });
            $dateFilter = 0;
        }

        if (isset($request->status) && $request->status != '' && $request->status != null) {
            $query->where('status', $request->status);
            $dateFilter = 0;
        }

        $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);

        $selects = ['id', 'mobile', 'number', 'txnid', 'amount', 'profit', 'charge', 'tds', 'gst', 'payid', 'refno', 'balance', 'status', 'rtype', 'trans_type', 'user_id', 'credit_by', 'created_at', 'product', 'option1', 'option3', 'option2', 'option5', 'option4', 'option6', 'option7', 'option8', 'option10', 'description', 'remark'];

        $selectData = [];
        foreach ($selects as $select) {
            $selectData[] = $select;
        }

        $exportData = $query->select($selectData);
        $count = intval($exportData->count());

        if (isset($request['length']) && $request->length != "-1") {
            $exportData->skip($request['start'])->take($request['length']);
        }

        $data = array(
            "draw"            => intval($request['draw']),
            "recordsTotal"    => $count,
            "recordsFiltered" => $count,
            "data"            => $exportData->get()
        );
        echo json_encode($data);
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
            'agentPhoneNumber' => 'required|numeric|digits:10',
            'userPan'          => 'required',
            'agentPinCode'     => 'nullable|numeric|digits:6',
            // New validation rules
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
        $validate = \Myhelper::FormValidator($rules, $post);
        if ($validate !== "no") {
            return $validate;
        }

        $gps_location = $post->gps_location;
        // Decode the encoded characters
        $gps_location = urldecode($gps_location);
        // Split the latitude and longitude
        list($lat, $lon) = explode('/', $gps_location);

        // Data to insert/update
        $data = [
            // Original fields

            'bcid'             => $this->generateBCAgentId(),
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
            'kioskcode'        => $post->user_id,
            'lat'              => $lat,
            'lon'              => $lon,
            'updated_at'       => now(),

            // New fields
            'middlename'       => $post->middlename ?? null,
            'alternatenumber'  => $post->alternatenumber ?? null,
            'telephone'        => $post->telephone ?? null,
            'district'         => $post->district ?? null,
            'area'             => $post->area ?? null,
            'shopaddress'      => $post->shopaddress ?? null,
            'shopstate'        => $post->shopstate ?? null,
            'shopcity'         => $post->shopcity ?? null,
            'shopdistrict'     => $post->shopdistrict ?? null,
            'shoparea'         => $post->shoparea ?? null,
            'shoppincode'      => $post->shoppincode ?? null,
            'dmt'              => $post->has('dmt') ? 1 : 0,
            'aeps'             => $post->has('aeps') ? 1 : 0,
            'cardpin'          => $post->has('cardpin') ? 1 : 0,
            'accountopen'      => $post->has('accountopen') ? 1 : 0,
            'tposserialno'     => $post->tposserialno ?? null,
            'temail'           => $post->temail ?? null,
            'taddress'         => $post->taddress ?? null,
            'taddress1'        => $post->taddress1 ?? null,
            'tstate'           => $post->tstate ?? null,
            'tcity'            => $post->tcity ?? null,
            'tpincode'         => $post->tpincode ?? null,
            'agenttype'        => $post->agenttype ?? 1, // Default to Normal Agent
            'agentbcid'        => $post->agentbcid ?? null,
        ];

        // Check if a record with the same kioskcode already exists
        $existingRecord = DB::table('emitra_agents')
            ->where('kioskcode', $post->user_id)
            ->first();

        if ($existingRecord) {
            // Update existing record
            $response = DB::table('emitra_agents')
                ->where('kioskcode', $post->user_id)
                ->update($data);

            $message = "Agent Nsdl account information updated. Please wait for approval";
        } else {
            // Add created_at for new records
            $data['created_at'] = now();
            // Insert new record
            $response = DB::table('emitra_agents')->insert($data);
            $message = "Agent Nsdl account onboarding process completed. Please wait for approval";
        }

        // Log the agent onboarding activity
        // DB::table('activity_logs')->insert([
        //     'user_id' => $post->user_id,
        //     'activity' => 'Agent onboarding form submitted',
        //     'details' => json_encode([
        //         'agent_name' => $post->agentName . ' ' . $post->agentLastName,
        //         'mobile' => $post->agentPhoneNumber,
        //         'agent_type' => $post->agenttype == 1 ? 'Normal Agent' : 'Direct Agent'
        //     ]),
        //     'created_at' => now()
        // ]);

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
    //     public function accountNsdlProcess(Request $post)
    //     {
    //         $rules = array(
    //     'Customername' => 'required',
    //     'Email'    => 'required',
    //     'mobileNo' => 'required',
    //     'panNo'    => 'required'
    // );

    // // dd($post['KIOSKCODEJ']);
    // // Fetch the onboarded PAN
    // $onboarded_pan = DB::table('emitra_agents')
    //     ->where('kioskcode', $post['KIOSKCODEJ'])
    //     ->where('status', 'approved')
    //     ->value('userPan');

    // $onboarded_agent_id = DB::table('emitra_agents')
    //     ->where('kioskcode', $post['KIOSKCODEJ'])
    //     ->where('status', 'approved')
    //     ->value('bcId');


    // // dd($onboarded_pan); // Check what value is being fetched

    // // Check if the PAN numbers match
    // // if ($onboarded_pan !== $post['panNo']) {
    // // //   return redirect()->back()->with('error', '');
    // // // return response()->json(['message'=>'The given PAN does not match the onboarded PAN.']);
    // // }

    // // // Assign the onboarded PAN to panNo
    // // $post['panNo'] = $onboarded_pan;


    //         $validator = \Validator::make($post->all(), $rules);
    //         if ($validator->fails()) {
    //             return response()->json(['errors'=>$validator->errors()], 422);
    //         }

    //         $post["name"]   = $post->Customername;
    //         $post["email"]  = $post->Email;
    //         $post["mobile"] = $post->mobileNo;
    //         $post["user_id"] = 0;
    //         $post["type"] = "nsdl";

    //         $newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes"));
    //         $today   = date('YmdHisU');
    //         $today2  = date('ymdHis');
    //         $mtime   = substr($today,0,-7);

    //         do {
    //             $partOne = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 2);    
    //             $partTwo = substr(str_shuffle("0123456789"), 0, 3);  
    //             $pref    = "R";
    //             $post["txnid"] = $pref.$today2.$partOne.$partTwo;
    //         } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

    //         $checkSumData = array(            
    //             "SSOID"     => $post->SSOID,
    //             "REQUESTID" => $post->txnid,
    //             "REQTIMESTAMP" => $mtime,
    //             "SSOTOKEN"  => $post->SSOTOKEN
    //         );
    //         $checkSumData_query = "toBeCheckSumString=".json_encode($checkSumData);

    //         $header = [
    //             "cache-control: no-cache",
    //             "content-type: application/x-www-form-urlencoded"
    //         ];

    //         $checkSumurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum";
    //         $checkSumcurl = \Myhelper::curl($checkSumurl, "POST", $checkSumData_query, $header, "no");

    //         if($checkSumcurl["error"] || $checkSumcurl["response"] == ""){
    //             return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
    //         }

    //         $reven =  "3206-120.00|3207-20.00";

    //         $databacktobackenc = array(            
    //             "MERCHANTCODE" => "MODISH2022",
    //             "REQTIMESTAMP" => $mtime,
    //             "SUBSERVICEID" => "",
    //             "REVENUEHEAD"  => $reven,
    //             "CONSUMERKEY"  => $post->txnid,
    //             "OFFICECODE"   => "MODISHHQ",
    //             "CONSUMERNAME" => $post->firstname." ".$post->middlename." ".$post->lastname,
    //             "COMMTYPE"     => "3",
    //             "SSOTOKEN"     => $post->SSOTOKEN,
    //             "REQUESTID"    => $post->txnid,
    //             "SSOID"        => $post->SSOID,
    //             "SERVICEID"    => $post->SERVICEID,
    //             "CHECKSUM"     => $checkSumcurl["response"],
    //         );

    //         $dataForEncryption = "toBeEncrypt=".json_encode($databacktobackenc);

    //         $encurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption";
    //         $enccurl = \Myhelper::curl($encurl, "POST", $dataForEncryption, $header, "no");

    //         if($enccurl["error"] || $enccurl["response"] == ""){
    //             return response()->json(['status' => 'ERR', 'message' => $enccurl["error"]."/".$enccurl["response"]]);
    //         }

    //         $backTobackEncryption = "encData=".$enccurl["response"];

    //         $backencurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/backtobackTransactionWithEncryptionA";
    //         $backenccurl = \Myhelper::curl($backencurl, "POST", $backTobackEncryption, $header, "no");

    //         if($backenccurl["error"] || $backenccurl["response"] == ""){
    //             return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
    //         }

    //         $datchecksumencbacktobackTransaction ="toBeDecrypt=".$backenccurl["response"];

    //         $backencdecurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption";
    //         $backencdeccurl = \Myhelper::curl($backencdecurl, "POST", $datchecksumencbacktobackTransaction, $header, "no");

    //         // dd($databacktobackenc, $backencdecurl, $backencdeccurl, $datchecksumencbacktobackTransaction, $header, $checkSumcurl);
    //         if($backencdeccurl["error"] || $backencdeccurl["response"] == ""){
    //             return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
    //         }

    //         $charactersencDataTransaction = json_decode($backencdeccurl["response"]);

    //         // dd($checkSumData, $checkSumData_query,$checkSumcurl, $header,$databacktobackenc, $encurl, $enccurl, $charactersencDataTransaction);
    //         $REQUESTID             = $charactersencDataTransaction->REQUESTID;
    //         $TRANSACTIONSTATUSCODE = $charactersencDataTransaction->TRANSACTIONSTATUSCODE;
    //         $RECEIPTNO             = $charactersencDataTransaction->RECEIPTNO;

    //         $TRANSACTIONID     = $charactersencDataTransaction->TRANSACTIONID;
    //         $TRANSAMT          = $charactersencDataTransaction->TRANSAMT;
    //         $REMAININGWALLET   = $charactersencDataTransaction->REMAININGWALLET;
    //         $MSG               = $charactersencDataTransaction->MSG;
    //         $TRANSACTIONSTATUS = $charactersencDataTransaction->TRANSACTIONSTATUS;

    //         $CHECKSUMf = $charactersencDataTransaction->CHECKSUM;

    //         if($TRANSACTIONSTATUSCODE != "200" || $TRANSACTIONSTATUS != "SUCCESS"){
    //             return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
    //         }

    //         // dd($charactersencDataTransaction, $checkSumData, $databacktobackenc, $datchecksumencbacktobackTransaction);

    //         $provider = Provider::where('recharge1', 'nsdlpaymentbank')->first();
    //         $insert = [
    //             'number'  => $post->mobile,
    //             'mobile'  => $post->mobile,
    //             'provider_id' => $provider->id,
    //             'api_id'  => $provider->api->id,
    //             'amount'  => 120,
    //             'txnid'   => $post->txnid,
    //             'payid'   => $TRANSACTIONID,
    //             'option1' => $post->name,
    //             'option3' => $post->KIOSKCODEJ,
    //             'option5' => $post->email,
    //             'option7' => $post->panNo,
    //             "option9" => $post->SSOTOKEN,
    //             "option10"=> $post->KIOSKDATA,
    //             'description' => $post->encData,
    //             'status'  => 'pending',
    //             'user_id'    => $post->user_id,
    //             'credit_by'  => 1,
    //             'rtype'      => 'main',
    //             'balance'    => "0",
    //             'trans_type' => 'none',
    //             'product'    => $post->type,
    //             'create_time'=> $post->mobile."-".date('ymdhis')
    //         ];

    //         $action  = Report::create($insert);

    //         if ($action) {
    //             $checksumStringCheck = "pin|nomineeName|nomineeDob|relationship|add2|add1|nomineeState|nomineeCity|add3|dateofbirth|pincode|customerLastName|mobileNo|customername|email|partnerRefNumber|clientid|dpid|customerDematId|bcid|applicationdocketnumber|tradingaccountnumber|bcagentid|customerRefNumber|partnerpan|partnerid|channelid|partnerCallBackURL|income|middleNameOfMother|panNo|kycFlag|maritalStatus|houseOfFatherOrSpouse";
    //             $name = explode(" ", $post->name);

    //             $jsonbody = '{
    //                 "nomineeName": "",
    //                 "nomineeDob": "",
    //                 "relationship": "",
    //                 "add1": "",
    //                 "add2": "",
    //                 "add3": "",
    //                 "pin": "",
    //                 "nomineeState": "",
    //                 "nomineeCity": "",
    //                 "customername": "",
    //                 "customerLastName": "",
    //                 "dateofbirth": "",
    //                 "pincode": "",
    //                 "email": "'.$post->email.'",
    //                 "mobileNo": "'.$post->mobileNo.'",
    //                 "maritalStatus": "",
    //                 "income": "",
    //                 "middleNameOfMother": "",
    //                 "houseOfFatherOrSpouse": "",
    //                 "kycFlag": "",
    //                 "panNo": "'.$post->panNo.'",
    //                 "channelid": "GpuqFObVfGrMfbtATuTY",
    //                 "partnerid": "xZ5W1JJbNV",
    //                 "applicationdocketnumber": "",
    //                 "dpid": "",
    //                 "clientid": "",
    //                 "partnerpan": "'.$onboarded_pan.'",
    //                 "tradingaccountnumber": "",
    //                 "partnerRefNumber": "",
    //                 "customerRefNumber": "",
    //                 "customerDematId": "",
    //                 "partnerCallBackURL": "https://rajpay.in/apply/nsdl/saving_account",
    //                 "bcid": "3653",
    //                 "bcagentid": "'.$onboarded_agent_id.'"
    //             }';

    //             $body = '{"nomineeDetails":{"nomineeName": "","nomineeDob": "","relationship": "","add1": "","add2": "","add3": "","pin": "","nomineeState": "","nomineeCity": ""},"personalDetails":{"customername": "","customerLastName": "","dateofbirth": "","pincode": "","email": "'.$post->email.'","mobileNo": "'.$post->mobileNo.'"},"otherDeatils":{"maritalStatus": "","income": "","middleNameOfMother": "","houseOfFatherOrSpouse": "","kycFlag": "","panNo": "'.$post->panNo.'"},"additionalParameters":{"channelid": "GpuqFObVfGrMfbtATuTY","partnerid": "xZ5W1JJbNV","applicationdocketnumber": "","dpid": "","clientid": "","partnerpan": "'.$onboarded_pan.'","tradingaccountnumber": "","partnerRefNumber": "","customerRefNumber": "","customerDematId": "","partnerCallBackURL": "https://rajpay.in/apply/nsdl/saving_account","bcid": "3653","bcagentid": "'.$onboarded_agent_id.'"}}';

    //             $arraybody = json_decode($jsonbody, true);
    //             $checksumStringCheckArray = explode("|", $checksumStringCheck);
    //             $checksumString = "";
    //             foreach ($checksumStringCheckArray as $key => $value) {
    //                 $checksumString .= $arraybody[$value];
    //             }
    //             // dd($body);
    //             $fkey="UBBluRkIcEyhPpVEpokWUvrAnykxvweUDVeUgArMJKWBUSKUTVnzDNTHzmipOPcgacCvpNQzyHXoOCUGTYwyKybCIMKvPUsjnZxKgbTRcLoRPoNSKeoYNDiipMqFiJjl";
    //             $signcs = base64_encode(hash_hmac('sha512', $checksumString, $fkey, true));

    //             $key= "UBBluRkIcEyhPpVE";
    //             $iv = "UBBluRkIcEyhPpVE";
    //             $encrypted = openssl_encrypt($body, 'aes-128-cbc', $key, 0, $iv);
    //             return response()->json(["statuscode" => "TXN", "message" => "https://nsdljiffy.co.in/jarvisjiffyBroker/accountOpen?partnerid=xZ5W1JJbNV&signcs=".$signcs."&encryptedStringCustomer=".urlencode($encrypted)]);
    //         }else{
    //             return response()->json(['statuscode' => "ERR", "message" => "Task Failed, please try again"]);
    //         }
    //     }
    public function accountNsdlProcess(Request $request)
    {
        // return 'ok';
        $rules = [
            'Customername' => 'required',
            'Email'        => 'required|email',
            'mobileNo'     => 'required',
            'panNo'        => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Fetch the onboarded PAN and agent ID
        $onboarded_pan = DB::table('emitra_agents')
            ->where('kioskcode', $request->KIOSKCODEJ)
            ->where('status', 'approved')
            ->value('userPan');

        $onboarded_agent_id = DB::table('emitra_agents')
            ->where('kioskcode', $request->KIOSKCODEJ)
            ->where('status', 'approved')
            ->value('bcId');

        // Optional: Validate that submitted PAN matches onboarded PAN
        // Uncomment if you want to enforce this validation
        /*
    if ($onboarded_pan !== $request->panNo) {
        return response()->json(['status' => 'ERR', 'message' => 'The given PAN does not match the onboarded PAN.']);
    }
    */

        // Prepare data
        $data = [
            "name"    => $request->Customername,
            "email"   => $request->Email,
            "mobile"  => $request->mobileNo,
            "user_id" => 0,
            "type"    => "nsdl"
        ];

        $newTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -10 minutes"));
        $today   = date('YmdHisU');
        $today2  = date('ymdHis');
        $mtime   = substr($today, 0, -7);

        // Generate unique transaction ID
        do {
            $partOne = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ"), 0, 2);
            $partTwo = substr(str_shuffle("0123456789"), 0, 3);
            $pref    = "R";
            $txnid   = $pref . $today2 . $partOne . $partTwo;
        } while (Report::where("txnid", "=", $txnid)->first() instanceof Report);

        $checkSumData = [
            "SSOID"        => $request->SSOID,
            "REQUESTID"    => $txnid,
            "REQTIMESTAMP" => $mtime,
            "SSOTOKEN"     => $request->SSOTOKEN
        ];
        $checkSumData_query = "toBeCheckSumString=" . json_encode($checkSumData);

        $header = [
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ];

        $checkSumurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum";
        $checkSumcurl = \Myhelper::curl($checkSumurl, "POST", $checkSumData_query, $header, "no");

        if ($checkSumcurl["error"] || $checkSumcurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        $reven = "3206-120.00|3207-20.00";

        // Fix: Use proper customer name (since firstname, middlename, lastname are not defined)
        $customerFullName = $request->Customername;

        // Check if a report already exists for this kiosk code
        $report = Report::where('option3', $request->mobileNo)->first();
        if ($report != null) {
            return response()->json(["statuscode" => "TXN", "message" => $report->url]);
        }

        $databacktobackenc = [
            "MERCHANTCODE" => "MODISH2022",
            "REQTIMESTAMP" => $mtime,
            "SUBSERVICEID" => "",
            "REVENUEHEAD"  => $reven,
            "CONSUMERKEY"  => $txnid,
            "OFFICECODE"   => "MODISHHQ",
            "CONSUMERNAME" => $customerFullName,
            "COMMTYPE"     => "3",
            "SSOTOKEN"     => $request->SSOTOKEN,
            "REQUESTID"    => $txnid,
            "SSOID"        => $request->SSOID,
            "SERVICEID"    => $request->SERVICEID,
            "CHECKSUM"     => $checkSumcurl["response"],
        ];

        $dataForEncryption = "toBeEncrypt=" . json_encode($databacktobackenc);

        $encurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption";
        $enccurl = \Myhelper::curl($encurl, "POST", $dataForEncryption, $header, "no");

        if ($enccurl["error"] || $enccurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => $enccurl["error"] . "/" . $enccurl["response"]]);
        }

        $backTobackEncryption = "encData=" . $enccurl["response"];

        $backencurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/backtobackTransactionWithEncryptionA";
        $backenccurl = \Myhelper::curl($backencurl, "POST", $backTobackEncryption, $header, "no");

        if ($backenccurl["error"] || $backenccurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        $datchecksumencbacktobackTransaction = "toBeDecrypt=" . $backenccurl["response"];

        $backencdecurl = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption";



        $backencdeccurl = \Myhelper::curl($backencdecurl, "POST", $datchecksumencbacktobackTransaction, $header, "no");

        if ($backencdeccurl["error"] || $backencdeccurl["response"] == "") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        $charactersencDataTransaction = json_decode($backencdeccurl["response"]);

        $REQUESTID             = $charactersencDataTransaction->REQUESTID;
        $TRANSACTIONSTATUSCODE = $charactersencDataTransaction->TRANSACTIONSTATUSCODE;
        $RECEIPTNO             = $charactersencDataTransaction->RECEIPTNO;
        $TRANSACTIONID         = $charactersencDataTransaction->TRANSACTIONID;
        $TRANSAMT              = $charactersencDataTransaction->TRANSAMT;
        $REMAININGWALLET       = $charactersencDataTransaction->REMAININGWALLET;
        $MSG                   = $charactersencDataTransaction->MSG;
        $TRANSACTIONSTATUS     = $charactersencDataTransaction->TRANSACTIONSTATUS;
        $CHECKSUMf             = $charactersencDataTransaction->CHECKSUM;

        if ($TRANSACTIONSTATUSCODE != "200" || $TRANSACTIONSTATUS != "SUCCESS") {
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, try again']);
        }

        $provider = Provider::where('recharge1', 'nsdlpaymentbank')->first();

        // Create report record
        $insert = [
            'number'      => $request->mobileNo,
            'mobile'      => $request->mobileNo,
            'provider_id' => $provider->id,
            'api_id'      => $provider->api->id,
            'amount'      => 120,
            'txnid'       => $txnid,
            'payid'       => $TRANSACTIONID,
            'option1'     => $request->Customername,
            'option3'     => $request->KIOSKCODEJ,
            'option5'     => $request->Email,
            'option7'     => $request->panNo,
            'option9'     => $request->SSOTOKEN,
            'option10'    => $request->KIOSKDATA,
            'description' => $request->encData ?? '',
            'status'      => 'pending',
            'user_id'     => 0,
            'credit_by'   => 1,
            'rtype'       => 'main',
            'balance'     => "0",
            'trans_type'  => 'none',
            'product'     => 'nsdl',

            'create_time' => $request->mobileNo . "-" . date('ymdhis')
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
            "email": "' . $request->Email . '",
            "mobileNo": "' . $request->mobileNo . '",
            "maritalStatus": "",
            "income": "",
            "middleNameOfMother": "",
            "houseOfFatherOrSpouse": "",
            "kycFlag": "",
            "panNo": "' . $request->panNo . '",
            "channelid": "GpuqFObVfGrMfbtATuTY",
            "partnerid": "xZ5W1JJbNV",
            "applicationdocketnumber": "",
            "dpid": "",
            "clientid": "",
            "partnerpan": "' . $onboarded_pan . '",
            "tradingaccountnumber": "",
            "partnerRefNumber": "",
            "customerRefNumber": "",
            "customerDematId": "",
            "partnerCallBackURL": "https://rajpay.in/apply/nsdl/saving_account",
            "bcid": "3653",
            "bcagentid": "' . $onboarded_agent_id . '"
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
                "email": "' . $request->Email . '",
                "mobileNo": "' . $request->mobileNo . '"
            },
            "otherDeatils": {
                "maritalStatus": "",
                "income": "",
                "middleNameOfMother": "",
                "houseOfFatherOrSpouse": "",
                "kycFlag": "",
                "panNo": "' . $request->panNo . '"
            },
            "additionalParameters": {
                "channelid": "GpuqFObVfGrMfbtATuTY",
                "partnerid": "xZ5W1JJbNV",
                "applicationdocketnumber": "",
                "dpid": "",
                "clientid": "",
                "partnerpan": "' . $onboarded_pan . '",
                "tradingaccountnumber": "",
                "partnerRefNumber": "",
                "customerRefNumber": "",
                "customerDematId": "",
                "partnerCallBackURL": "https://rajpay.in/apply/nsdl/saving_account",
                "bcid": "3653",
                "bcagentid": "' . $onboarded_agent_id . '"
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

            $redirectUrl = "https://nsdljiffy.co.in/jarvisjiffyBroker/accountOpen?partnerid=xZ5W1JJbNV&signcs=" . $signcs . "&encryptedStringCustomer=" . urlencode($encrypted);

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

    public function nsdlreport(Request $request)
    {
        if (!$request->has("fromdate") || empty($request->fromdate) || $request->fromdate == NULL || $request->fromdate == "null") {
            $request['fromdate'] = date("Y-m-d");
        }

        if (!$request->has("todate") || empty($request->todate) || $request->todate == NULL || $request->todate == "null") {
            $request['todate'] = $request->fromdate;
        }

        $query  = \DB::table("reports")
            ->orderBy('id', 'desc')
            ->where('rtype', "main")
            ->where('option3', $request->agent)
            ->where('product', 'nsdl');

        if (!empty($request->searchtext)) {
            $serachDatas = ['txnid', 'refno'];
            $query->where(function ($q) use ($request, $serachDatas, $tables) {
                foreach ($serachDatas as $value) {
                    $q->orWhere($value, 'like', '%' . $request->searchtext . '%');
                }
            });
            $dateFilter = 0;
        }

        if (isset($request->status) && $request->status != '' && $request->status != null) {
            $query->where('status', $request->status);
            $dateFilter = 0;
        }

        $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);

        $selects = ['id', 'mobile', 'number', 'url', 'txnid', 'amount', 'profit', 'charge', 'tds', 'gst', 'payid', 'refno', 'balance', 'status', 'rtype', 'trans_type', 'user_id', 'credit_by', 'created_at', 'product', 'option1', 'option3', 'option2', 'option5', 'option4', 'option6', 'option7', 'option8', 'option10', 'description', 'remark'];

        $selectData = [];
        foreach ($selects as $select) {
            $selectData[] = $select;
        }

        $exportData = $query->select($selectData);
        $count = intval($exportData->count());

        if (isset($request['length']) && $request->length != "-1") {
            $exportData->skip($request['start'])->take($request['length']);
        }

        $data = array(
            "draw"            => intval($request['draw']),
            "recordsTotal"    => $count,
            "recordsFiltered" => $count,
            "data"            => $exportData->get()
        );
        echo json_encode($data);
    }
    protected function generateBCAgentId()
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
    public function updateAgent(Request $request)
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

            // Remove empty values from the data (so we don't update with null if it's optional)
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


    public function get_agent_details(Request $request)
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
}
