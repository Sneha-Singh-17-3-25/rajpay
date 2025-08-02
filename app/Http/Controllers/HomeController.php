<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Circle;
use App\User;
use App\Model\Report;
use App\Model\Complaint;
use App\Model\Api;
use App\Model\Apitoken;
use App\Model\Contact;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['getmysendip', 'setpermissions','checkcommission', 'sendotp']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $user = User::find(session("loginid"));
        session(["role" => $user->role->slug]);
        if(\Myhelper::hasNotRole(['whitelable', 'md', 'distributor', 'retailer', 'asm', 'statehead'])){
            $user = User::whereHas('role', function ($q){
                $q->where('slug', 'admin');
            })->first(['id']);

            session(['sessionUserId' => $user->id]);
        }else{
            session(['sessionUserId' => \Auth::id()]);
        }
        
        if(!session('parentData')){
            session()->put('parentData', \Myhelper::getParents(session('sessionUserId')));
        }

        $data['state'] = Circle::all();
        $roles = ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'other'];

        foreach ($roles as $role) {
            if($role == "other"){
                $data[$role] = User::whereHas('role', function($q){
                    $q->whereNotIn('slug', ['whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'admin']);
                })->whereIn('id', session('parentData'))->whereIn('kyc', ['verified'])->count();
            }else{
                $data[$role] = User::whereHas('role', function($q) use($role){
                    $q->where('slug', $role);
                })->whereIn('id', session('parentData'))->whereIn('kyc', ['verified'])->count();
            }
        }

        $notice = \DB::table('companydatas')->where('company_id', $user->company_id)->first(['notice', 'news', 'number', 'email']);
        if($notice){
            $data['notice'] = $notice->notice;
            $data['news'] = $notice->news;
            $data['supportnumber'] = $notice->number;
            $data['supportemail']  = $notice->email;
            session(["news" => $notice->news]);
        }else{
            $data['notice'] = "";
            $data['news']   = "";
            $data['supportnumber'] = "";
            $data['supportemail']  = "";
            session(["news" => ""]);
        }

        $pincheck = \DB::table('portal_settings')->where('code', "pincheck")->first();
        session(["pincheck" => "no"]);
        
        if($pincheck){
            if($pincheck->value == "yes"){
                if(!\Myhelper::can('pin_check')){
                    session(["pincheck" => $pincheck->value]);
                }
            }
        }

        $otpcheck = \DB::table('portal_settings')->where('code', "otppayout")->first();
        if($otpcheck){
            if($otpcheck->value == "yes"){
                session(["otppayout" => $otpcheck->value]);
            }else{
                session(["otppayout" => "no"]); 
            }
        }else{
            session(["otppayout" => "no"]); 
        }

        return view('home')->with($data);
    }

    public function get_remote_macaddr( $ip ) {
        return strtoupper( exec( "arp -a " . $ip . " | awk '{print $4 }'"));
    }
    
    public function insurance()
    {
        return view('insurance');
    }
    
    public function coming($type)
    {
        return view('comingsoon');
    }
    
    public function vaccine()
    {
        return view('vaccine');
    }
    
    public function getmysendip()
    {
        $billapi = \App\Model\Api::where('code', 'mhbill')->first();
        $agents   = \App\Model\Mahaagent::get();
        foreach ($agents as $agent) {
            if(!$agent->bbps_agent_id){
                $gpsdata = geoip($post->ip());
                $burl  = $billapi->url."RegBBPSAgent";

                $json_data = [
                    "requestby"     => $billapi->username,
                    "securityKey"   => $billapi->password,
                    "name"          => $agent->bc_f_name." ".$agent->bc_l_name,
                    "contactperson" => $agent->bc_f_name." ".$agent->bc_l_name,
                    "mobileNumber"  => $agent->phone1,
                    'agentshopname' => $agent->shopname,
                    "businesstype"  => $agent->shopType,
                    "address1"      => $agent->bc_address,
                    "address2"      => $agent->bc_city,
                    "state"         => $agent->bc_state,
                    "city"          => $agent->bc_district,
                    "pincode"       => $agent->bc_pincode,
                    "latitude"      => sprintf('%0.4f', $gpsdata->lat),
                    "longitude"     => sprintf('%0.4f', $gpsdata->lon),
                    'email'         => $agent->emailid
                ];
                
                $header = array(
                    "authorization: Basic ".$billapi->optional1,
                    "cache-control: no-cache",
                    "content-type: application/json"
                );

                $bbpsresult = \Myhelper::curl($burl, "POST", json_encode($json_data), $header, "yes", 'MahaBill', $agent->phone1);
                if($bbpsresult['response'] != ''){
                    $response = json_decode($bbpsresult['response']);
                    if(isset($response->Data)){
                        $datas = $response->Data;
                        if(!empty($datas)){
                            \App\Model\Mahaagent::where('id', $agents->id)->update(['bbps_agent_id' => $datas[0]->agentid]);
                        }
                    }
                }
            }
        }
    }

    public function getbalance()
    {
        $data['apibalance'] = 0;
        $data['myrcapibalance'] = 0;
        
        // $api = Api::where('code', 'dmt1')->first();
        // $parameter = [
        // "token" =>  $api->username,
        // "request" =>  [
        //         "account_type" => "ALL",
        //         "account_no" => ""
        //     ]
        // ];
        
        // $result = \Myhelper::curl("https://www.instantpay.in/ws/user/balance", 'POST', json_encode($parameter), array(
        //         "Accept: application/json",
        //         "Cache-Control: no-cache",
        //         "Content-Type: application/json"
        //     ), 'no', []);
        // $response = json_decode($result['response']);
        // if($result['response'] != '' && isset($response->data->POOL->bal_available)){
        //     $data['apibalance'] = $response->data->POOL->bal_available;
        // }else{
        // }
        
        // $result = \Myhelper::curl("https://myrc.in/v3/recharge/balance?username=501297&token=a26c0addc35ebd3585c8647a45651e0b&format=json", 'GET',"", [], 'no');
        // $response = json_decode($result['response']);
        // //dd($response);
        // if($result['response'] != '' && isset($response->balance)){
        //     $data['myrcapibalance'] = $response->balance;
        // }else{
        //     $data['myrcapibalance'] = 0;
        // }

        $data['mainwallet'] = \Auth::user()->mainwallet;

        if(\Myhelper::hasRole("admin")){
            $data['aepswallet'] = round(User::where('id', '!=', \Auth::id())->sum('aepswallet'), 2);
        }else{
            $data['aepswallet'] = \Auth::user()->aepswallet;
        }

        $data['downlinebalance'] = round(User::where('id', '!=', \Auth::id())->sum('mainwallet'), 2);
        $data['lockedamount'] = round(User::where('id', '!=', \Auth::id())->sum('lockedamount'), 2);
        return response()->json($data);
    }

    public function mydata()
    {
        $data['fundrequest']   = \App\Model\Fundreport::where('credited_by', \Auth::id())->where('status', 'pending')->count();
        $data['payoutrequest'] = \DB::table("aepsreports")->where('product', "payout")->where('status', 'pending')->count();
        $data['member'] = \App\User::where('status', 'block')->where('kyc', 'pending')->count();
        $data['kycpending']      = User::where('kyc', 'pending')->whereHas('role', function ($q){
            $q->whereIn('slug', ['statehead', 'asm', 'whitelable', 'md', 'distributor', 'retailer']);
        })->count();
        $data['kycsubmitted']    = User::where('kyc', 'submitted')->whereHas('role', function ($q){
            $q->whereIn('slug', ['statehead', 'asm', 'whitelable', 'md', 'distributor', 'retailer']);
        })->count();
        $data['kycrejected']     = User::where('kyc', 'rejected')->whereHas('role', function ($q){
            $q->whereIn('slug', ['statehead', 'asm', 'whitelable', 'md', 'distributor', 'retailer']);
        })->count();
        $data['utiids'] = \DB::table("utiids")->where('status', 'pending')->count();
        $data['complaint'] = Complaint::where('status', 'pending')->count();
        $data['payoutbank']= Contact::where('status', 'pending')->count();
        $data['pancard'] = Report::where('status', 'pending')->where("product", "pancard")->count();
        $data['billpay'] = Report::where('status', 'pending')->where("product", "offlinebillpay")->count();
        $data['transactionCount'] = $data['pancard'] + $data['billpay'];
        $data['pendingApprovals'] = $data['complaint'] + $data['payoutbank'];
        return response()->json($data);      
    }

    public function statics(Request $post)
    {
        if(\Myhelper::hasNotRole("admin") && \Myhelper::hasRole("retailer")){
            $userid = \Auth::id();
        }else{
            $userid = $post->userid;
        }

        if(\Myhelper::hasNotRole("admin") && !in_array($userid, session("parentData"))){
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
        }

        $product = [
            'recharge',
            'bbps',
            'billpay',
            'dmt',
            'pancard',
            'iaeps',
            'faeps',
            'iaadharpay',
            'faadharpay',
            'matm',
            'payout'
        ];

        $statuscount = [ 'success' => ['success'] , 'pending' => ['pending'], 'failed' => ['failed', 'reversed']];

        foreach ($product as $value) {
            foreach ($statuscount as $keys => $values) {
                switch ($value) {
                    case 'recharge':
                    case 'dmt':
                    case 'bbps':
                    case 'pancard':
                    case 'billpay':
                        $query = \DB::table('reports')->where("rtype", "main");
                        break;
                    
                    default:
                        $query = \DB::table('aepsreports')->where("rtype", "main");
                        break;
                }
                if((\Myhelper::hasRole("admin") && $userid != 0) || ($userid != 0 && in_array($userid ,  session("parentData")))){
                    $query->where("user_id", $userid);
                }

                if((isset($post->fromdate) && !empty($post->fromdate)) && (isset($post->todate) && !empty($post->todate))){
                    if($post->fromdate == $post->todate){
                        $query->whereDate('created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
                    }else{
                        $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $post->todate)->addDay(1)->format('Y-m-d')]);
                    }
                }elseif (isset($post->fromdate) && !empty($post->fromdate)) {
                    $query->whereDate('created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
                }else{
                    $query->whereDate('created_at','=', date('Y-m-d'));
                }

                switch ($value) {
                    case 'recharge':
                        $query->where('product', 'recharge');
                        break;
                    
                    case 'bbps':
                        $query->where('product', 'billpay');
                        break;
                    
                    case 'billpay':
                        $query->where('product', 'offlinebillpay');
                        break;

                    case 'dmt':
                        $query->where('product', 'dmt');
                        break;

                    case 'pancard':
                        $query->where('product', 'pancard');
                        break;

                    case 'iaeps':
                        $query->where('product', 'aeps')->where('api_id', '3')->where('option1', 'CW');
                        break;

                    case 'faeps':
                        $query->where('product', 'aeps')->where('api_id', '2')->where('option1', 'CW');
                        break;

                    case 'matm':
                        $query->where('product', 'matm');
                        break;

                    case 'iaadharpay':
                        $query->where('product', 'aeps')->where('api_id', '3')->where('option1', 'M');
                        break;

                    case 'faadharpay':
                        $query->where('product', 'aeps')->where('api_id', '2')->where('option1', 'M');
                        break;
                    
                    case 'payout':
                        $query->where('option1', 'bank');
                        break;
                }
                $data[$value.$keys] = $query->whereIn('status', $values)->count();
                $data[$value.$keys."amt"] = round($query->whereIn('status', $values)->sum("amount"), 2);
            }
        }

        return response()->json($data);      
    }

    public function mycommission(Request $post)
    {
        if(\Myhelper::hasRole("retailer")){
            $userid = \Auth::id();
        }else{
            $userid = $post->userid;
        }

        if(\Myhelper::hasNotRole("admin") && !in_array($userid, session("parentData"))){
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
        }

        $product = [
            'recharge',
            'bbps',
            'billpay',
            'dmt',
            'pancard',
            'iaeps',
            'faeps',
            'matm',
            'payout'
        ];

        foreach ($product as $value) {
            switch ($value) {
                case 'recharge':
                case 'dmt':
                case 'bbps':
                case 'pancard':
                    $query = \DB::table('reports');
                    break;
                
                default:
                    $query = \DB::table('aepsreports');
                    break;
            }

            if($userid != 0){
                $query->where("user_id", $userid);
            }

            if((isset($post->fromdate) && !empty($post->fromdate)) && (isset($post->todate) && !empty($post->todate))){
                if($post->fromdate == $post->todate){
                    $query->whereDate('created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
                }else{
                    $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $post->todate)->addDay(1)->format('Y-m-d')]);
                }
            }elseif (isset($post->fromdate) && !empty($post->fromdate)) {
                $query->whereDate('created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
            }else{
                $query->whereDate('created_at','=', date('Y-m-d'));
            }
            $query->where('status', "success");

            switch ($value) {
                case 'recharge':
                    $data[$value."commission"] = round($query->where("product", "recharge")->where("rtype", "main")->sum("profit") + $query->where("product", "recharge")->where("rtype", "commission")->sum("amount"), 2);
                    break;
                
                case 'billpay':
                    $data[$value."commission"] = round($query->where("product", "offlinebillpay")->where("rtype", "main")->sum("profit") + $query->where("product", "offlinebillpay")->where("rtype", "commission")->sum("amount"), 2);
                    break;
                
                case 'bbps':
                    $data[$value."commission"] = round($query->where("product", "billpay")->where("rtype", "main")->sum("profit") + $query->where("product", "billpay")->where("rtype", "commission")->sum("amount"), 2);
                    break;

                case 'dmt':
                    $data[$value."commission"] = round($query->where("product", "dmt")->where("rtype", "main")->sum("profit") + $query->where("product", "dmt")->where("rtype", "commission")->sum("amount"), 2);
                    break;

                case 'pancard':
                    $data[$value."commission"] = round($query->where("product", "pancard")->where("rtype", "main")->sum("profit") + $query->where("product", "pancard")->where("rtype", "commission")->sum("amount"), 2);
                    break;

                case 'iaeps':
                    $data[$value."commission"] = round($query->where('product', 'aeps')->where('api_id', '3')->where('option1', 'CW')->where("rtype", "main")->sum("profit") + $query->where("product", "aeps")->where('api_id', '3')->where('option1', 'CW')->where("rtype", "commission")->sum("amount"), 2);
                    break;

                case 'faeps':
                    $data[$value."commission"] = round($query->where('product', 'aeps')->where('api_id', '2')->where('option1', 'CW')->where("rtype", "main")->sum("profit") + $query->where("product", "aeps")->where('api_id', '2')->where('option1', 'CW')->where("rtype", "commission")->sum("amount"), 2);
                    break;

                case 'matm':
                    $data[$value."commission"] = round($query->where("product", "matm")->where("rtype", "main")->sum("profit") + $query->where("product", "matm")->where("rtype", "commission")->sum("amount"), 2);
                    break;

                case 'payout':
                    $data[$value."commission"] = round($query->where('product', 'payout')->sum("charge"), 2);
                    break;
            }
        }

        return response()->json($data);      
    }

    public function useronboard(Request $post)
    {
        $product = [
            'day',
            'sevenday',
            '30day',
            'all'
        ];

        foreach ($product as $value) {
            $query = \DB::table('fingagents');

            if(\Myhelper::hasRole("apiuser")){
                $query->where("user_id", \Auth::id());
            }

            switch ($value) {
                case 'day':
                    $query->whereDate('created_at','=', date('Y-m-d'));
                    break;
                
                case 'sevenday':
                    $query->whereBetween('created_at', [Carbon::now()->subDays(7)->format('Y-m-d'), Carbon::now()->format('Y-m-d')]);
                    break;

                case '30day':
                    $query->whereBetween('created_at', [Carbon::now()->subDays(30)->format('Y-m-d'), Carbon::now()->format('Y-m-d')]);
                    break;
            }

            $data["onboard".$value] = $query->count();
        }

        return response()->json($data);      
    }

    public function checkcommission(Request $post)
    {
        dd(\Myhelper::commission(\App\Model\Microatmreport::find('68016')));
    }

    public function sendotp(Request $post)
    {
        $rules = array(
            "for"     => "required" 
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        
        if(session("otppayout") == "no"){
            return response()->json(['status' => 'PASS', 'message' => "Pin generate token sent successfully"]);
        }

        $user = \App\User::where('id', $post->user_id)->first();
        $sendotp = \DB::table('password_resets')->where('mobile', $user->mobile)->first();
        if(!$sendotp){
            if($user){
                $otp = rand(111111, 999999);
                $mydata['otp']    = $otp;
                $mydata['mobile'] = $user->mobile;
                $send = \Myhelper::notification($post->for, $mydata);

                if($send == "success"){
                    $user = \DB::table('password_resets')->insert([
                        'mobile' => $user->mobile,
                        'token' => \Myhelper::encrypt($otp, "vppay##01012022"),
                        'last_activity' => time()
                    ]);
                
                    return response()->json(['status' => 'TXN', 'message' => "Otp sent successfully"]);
                }else{
                    return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
                }
            }else{
                return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"]);
            }  
        }else{
            if($sendotp->resend <3){
                if($user){
                    $otp = rand(111111, 999999);
                    $mydata['otp']    = $otp;
                    $mydata['mobile'] = $user->mobile;
                    $send = \Myhelper::notification($post->for, $mydata);

                    if($send == "success"){
                        $user = \DB::table('password_resets')->where('mobile', $user->mobile)->update([
                            'token' => \Myhelper::encrypt($otp, "vppay##01012022"),
                            'last_activity' => time(),
                            "resend" => ($sendotp->resend + 1)
                        ]);
                    
                        return response()->json(['status' => 'TXN', 'message' => "Otp sent successfully"]);
                    }else{
                        return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
                    }
                }else{
                    return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"]);
                }
            }else{
                return response()->json(['status' => 'ERR', 'message' => "Otp resend limit exceeded, contact helpdesk"]);
            }
        }
    }

    public function setpermissions()
    {
        $users = User::whereHas('role', function($q){ $q->whereIn('slug' ,['whitelable','md', 'distributor','retailer', 'apiuser']); })->get();
        foreach ($users as $user) {
            $inserts = [];
            $insert  = [];
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id',$user->role_id)->get();

            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $user->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
            }
            \DB::table('user_permissions')->where("user_id", $user->id)->delete();
            \DB::table('user_permissions')->insert($inserts);
        }
    }

    public function setscheme()
    {
        $users = User::whereHas('role', function($q){ $q->where('slug', '!=' ,'admin'); })->get();

        foreach ($users as $user) {
            $scheme  = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $user->role_id)->first();
            if ($scheme) {
                User::where('id', $user->id)->update(['scheme_id' => $scheme->permission_id]);
            }
        }
    }

    public function getBillerData()
    {
        $billavenue = Api::where('code', 'bbps_avenue')->first();
        do {
            $post['txnid'] = "RJPDMT".Str::random(17).sprintf("%012d", substr(date("Y"), -1).date("z").date('Hs'));
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        $url = $billavenue->url."extMdmCntrl/mdmRequest/xml";

        $biller = \DB::table("npcibilleralls")->get();

        $parameter = '<?xml version="1.0" encoding="UTF-8"?> <billerInfoRequest>';
        foreach ($biller as $billers) {
            $parameter .= "<billerId>".$billers->billerId."</billerId>";
        }
        
        $parameter .= " </billerInfoRequest>";
        $encrypt_xml_data = \Myhelper::encrypt($parameter, $billavenue->password);
        
        $data['accessCode'] = $billavenue->username;
        $data['requestId']  = $txnid;
        $data['encRequest'] = $encrypt_xml_data;
        $data['ver'] = "1.0";
        $data['instituteId'] = $billavenue->optional1;
        $parameters = http_build_query($data);

        $result   = \Myhelper::curl($url, "POST", $parameters, [], "no");
        $responsedata = \Myhelper::decrypt($result['response'], $billavenue->password);
        $xml      = simplexml_load_string($responsedata);
        $response = json_decode(json_encode((array) $xml), true);
        \DB::table("npcibillerall_bbpss")->truncate();

        $inserts = [];
        foreach($response['biller'] as $billers){
            $insert = [];
            foreach($billers as $key => $value){
                if(is_array($value)){
                    $insert[$key] = json_encode($value);
                }else{
                    $insert[$key] = $value;
                }
            }
            try {
                \DB::table("npcibillerall_bbpss")->insert($insert);
            } catch (\Exception $e) {
                \DB::table('log_500')->insert([
                    'log' => $e->getMessage()."/".json_decode($insert),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        return response()->json(['status' => "success"], 200);
    }

    public function setBillerData()
    {
        $billers = \DB::table("npcibillerall_bbpss")->whereIn("billerCategory", ["Loan Repayment","Municipal Taxes","Broadband Postpaid","Gas","Insurance","Fastag","Cable TV","Electricity","Landline Postpaid","Mobile Postpaid","Credit Card","Water","Municipal Services","Life Insurance","Health Insurance"])->get();

        foreach ($billers as $biller) {
            $agt = "no";
            if (str_contains($biller->billerPaymentChannels, '"paymentChannelName":"AGT"')) { 
                $agt = "yes";
            }

            $parameters = "";
            $minlength  = "";
            $maxlength  = "";
            $regex = "";
            $fieldtype   = "";

            $params = json_decode($biller->billerInputParams);
            if(is_array($params->paramInfo)){
                foreach ($params->paramInfo as $paramInfo) {
                    if($paramInfo->isOptional == "false"){
                        $parameters  .= $paramInfo->paramName.",";
                        $minlength   .= $paramInfo->minLength.",";
                        $maxlength   .= $paramInfo->maxLength.",";
                        $regex       .= isset($paramInfo->regEx)?$paramInfo->regEx.",":",";
                        $fieldtype   .= $paramInfo->dataType.",";
                    }
                }
            }else{
                $parameters = $params->paramInfo->paramName;
                $minlength  = $params->paramInfo->minLength;
                $maxlength  = $params->paramInfo->maxLength;
                $regex      = isset($params->paramInfo->regEx)?$params->paramInfo->regEx:"";
                $fieldtype  = $params->paramInfo->dataType;
            }

            $billPayType = "fetchpay";
            switch ($biller->billerFetchRequiremet) {
                case 'NOT_SUPPORTED':
                    switch ($biller->billerSupportBillValidation) {
                        case 'NOT_SUPPORTED':
                        case 'OPTIONAL':
                            $billPayType = "quickpay";
                            break;
                        
                        default:
                            $billPayType = "validatepay";
                            break;
                    }
                    break;
                
                default:
                    $billPayType = "fetchpay";
                    break;
            }

            $provider = \DB::table('providers')->where("recharge3", $biller->billerId)->first();
            if($provider){
                \DB::table('providers')->where("recharge3", $biller->billerId)->update([
                    "paramname"   => trim($parameters, ","),
                    "minlength"   => trim($minlength, ","),
                    "maxlength"   => trim($maxlength, ","),
                    "regex"       => trim($regex, ","),
                    "fieldtype"   => trim($fieldtype, ","),
                    "billerAdhoc" => $biller->billerAdhoc,
                    "billerPaymentExactness" => strtolower(str_replace(" ", "", $biller->billerPaymentExactness)),
                    "billerPaymentChannels"  => $agt,
                    "billPayType" => $billPayType
                ]);
            }else{
                switch ($biller->billerCategory) {
                    case 'Loan Repayment':
                        $category = "loanrepay";
                        break;
                    
                    case 'Credit Card':
                        $category = "creditcard";
                        break;
                    
                    case 'Mobile Postpaid':
                        $category = "postpaid";
                        break;
                    
                    case 'Landline Postpaid':
                        $category = "landline";
                        break;
                    
                    case 'Electricity':
                        $category = "electricity";
                        break;
                    
                    case 'Cable TV':
                        $category = "cable";
                        break;
                    
                    case 'Fastag':
                        $category = "fasttag";
                        break;
                    
                    case 'Insurance':
                        $category = "insurance";
                        break;
                    
                    case 'Gas':
                        $category = "gas";
                        break;
                    
                    case 'Broadband Postpaid':
                        $category = "broadband";
                        break;
                    
                    case 'Municipal Services':
                        $category = "muncipal";
                        break;
                    
                    case 'Municipal Taxes':
                        $category = "tax";
                        break;
                    
                    case 'Water':
                        $category = "water";
                        break;
                    
                    case 'Life Insurance':
                        $category = "lifeinsurance";
                        break;
                    
                    case 'Health Insurance':
                        $category = "healthinsurance";
                        break;
                    
                    default:
                        $category = "";
                        break;
                }

                if($category != ""){
                    \DB::table('providers')->insert([
                        "name" => $biller->billerName,
                        "minlength"   => trim($minlength, ","),
                        "maxlength"   => trim($maxlength, ","),
                        "regex"       => trim($regex, ","),
                        "fieldtype"   => trim($fieldtype, ","),
                        "recharge1" => $biller->billerId,
                        "recharge2" => $biller->billerId,
                        "recharge3" => $biller->billerId,
                        "type"   => $category,
                        "api_id" => 21,
                        "paramname"   => trim($parameters, ","),
                        "billerAdhoc" => $biller->billerAdhoc,
                        "billerPaymentExactness" => strtolower(str_replace(" ", "", $biller->billerPaymentExactness)),
                        "billerPaymentChannels"  => $agt,
                        "billPayType" => $billPayType
                    ]);
                }
            }
        }
    }
}
