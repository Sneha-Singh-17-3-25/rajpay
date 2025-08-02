<?php

namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Mahaagent;
use App\Model\Api;
use App\Model\Microatmreport;
use App\Model\Utiid;
use App\Model\Provider;
use App\Model\Fingagent;
use \App\Model\Securedata;
use App\Model\Aepsreport;
use App\Model\Companydata;
use App\Model\Pindata;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $post)
    {
        // $rules = array(
        //     'password' => 'required',
        //     'mobile'  =>'required|numeric',
        // );

        // $validate = \Myhelper::FormValidator($rules, $post);
        // if($validate != "no"){
        //     return $validate;
        // }

        // $user = User::where('mobile', $post->mobile)->with(['role'])->first();
        // if(!$user){
        //     return response()->json(['status' => 'ERR', 'message' => "Your aren't registred with us." ]);
        // }
        
        // if (!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password])) {
        //     return response()->json(['status' => 'ERR', 'message' => 'Username and password is incorrect']);
        // }

        $rules = array(
        'password' => 'required',
        'mobile'  => 'required', // Changed from 'numeric' to allow email for admin
    );

    $validate = \Myhelper::FormValidator($rules, $post);
    if($validate != "no"){
        return $validate;
    }

    // Check if it's email (for admin) or mobile
    if(filter_var($post->mobile, FILTER_VALIDATE_EMAIL)) {
        $user = User::where('email', $post->mobile)->with(['role'])->first();
        $authField = 'email';
    } else {
        $user = User::where('mobile', $post->mobile)->with(['role'])->first();
        $authField = 'mobile';
    }

    if(!$user){
        return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us." ]);
    }
    
    if (!\Auth::validate([$authField => $post->mobile, 'password' => $post->password])) {
        return response()->json(['status' => 'ERR', 'message' => 'Username and password is incorrect']);
    }

    


        if(!$post->has('otp') && ($user->device_id != $post->device_id) && !in_array($post->mobile, ["9971702308", "9587887702"])){

            $otp = rand(111111, 999999);
            $mydata['otp']    = $otp;
            $mydata['mobile'] = $post->mobile;
            $mydata['name']   = $user->name;
            $mydata['email']  = $user->email;
            $send = \Myhelper::notification("otp", $mydata);

            if($send == 'success'){
                \DB::table('password_resets')->insert([
                    'mobile' => $post->mobile,
                    'token' => \Myhelper::encrypt($otp, "sahajmoney@@##2025500"),
                    'last_activity' => time()
                ]);
                return response()->json(['status' => 'OTP', 'message' => 'otp sent on your mobile number']);
            }else{
                return response()->json(['status' => 'ERR', 'message' => 'Please contact your service provider provider']);
            }
        }

        if(($user->device_id != $post->device_id) && !in_array($post->mobile, ["9971702308", "9587887702"])){
            $otpSend = \DB::table('password_resets')->where("mobile", $post->mobile)->where("token", \Myhelper::encrypt($post->otp, "sahajmoney@@##2025500"))->first();

            if(!$otpSend){
                return response()->json(['status' => 'ERR', 'message' => 'Incorrect Otp']);
            }

            if (!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password, 'status' => "active"])) {
                return response()->json(['status' => 'ERR', 'message' => 'Incorrect Otp or Your account currently de-activated']);
            }

            \DB::table('password_resets')->where("mobile", $post->mobile)->where("token", \Myhelper::encrypt($post->otp, "sahajmoney@@##2025500"))->delete();
        }else{
            if (!\Auth::validate(['mobile' => $post->mobile, 'password' => $post->password, 'status' => "active"])) {
                return response()->json(['status' => 'ERR', 'message' => 'Your account currently de-activated, please contact administrator']);
            }
        }

        $apptoken = Securedata::where('user_id', $user->id)->first();
        if(!$apptoken){
            do {
                $string = str_random(40);
            } while (Securedata::where("apptoken", "=", $string)->first() instanceof Securedata);

            try {
                $apptoken = Securedata::create([
                    'apptoken' => $string,
                    'ip'       => $post->ip(),
                    'user_id'  => $user->id,
                    'last_activity' => time()
                ]);

                \App\Model\Loginsession::create([
                    'user_id'    => $user->id,
                    'ip_address' => $post->ip(),
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'gps_location' => $post->gps_location
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERR', 'message' => 'Already Logged In']);
            }
        }

        User::where('mobile', $post->mobile)->update(['device_id' => $post->device_id]);
        $user = \DB::table("users")->leftjoin('roles', "roles.id" , "users.role_id")->leftjoin('companies', "companies.id" , "users.company_id")->where('mobile', $post->mobile)->first(['users.id','users.name','users.email','users.mobile','users.company_id','users.address','users.shopname','users.gstin','users.city','users.state','users.pincode','users.pancard','users.aadharcard','users.kyc','users.resetpwd','users.mainwallet','users.aepswallet','roles.name as rolename', 'companies.companyname','users.pancardpic','users.aadharcardpicfront','users.aadharcardpicback', 'users.passbook','users.profile', 'users.otherdoc']);
        $user->apptoken    = $apptoken->apptoken;
        $utiid = Utiid::where('user_id', $user->id)->first();
        $news  = Companydata::where('company_id', $user->company_id)->first();  
        
        if($news){
            $user->news = $news->news;
            $user->billnotice = $news->billnotice;
            $user->supportnumber = $news->number;
            $user->supportemail = $news->email;
        }
        if($utiid){
            $user->utiid = $utiid->vleid;
            $user->utiidtxnid = $utiid->id;
            $user->utiidstatus = $utiid->status;
        }
        $user->tokenamount = '107';
        $user->status = "TXN";
        
        $user->tpin = "no";
        if($this->pinbased() == "yes"){
            if(!\Myhelper::can('pin_check', $user->id)){
                $user->tpin  = "yes";
            }
        }

        $user->otppayout = "no";
        $otpcheck = \DB::table('portal_settings')->where('code', "otppayout")->first();
        if($otpcheck){
            if($otpcheck->value == "yes"){
                $user->otppayout = "yes";
            }
        }
        return response()->json($user);
    }
    
    public function logout(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $delete = Securedata::where('user_id', $post->user_id)->where('apptoken', $post->apptoken)->delete();
        if($delete){
            return response()->json(['status' => 'TXN', 'message' => 'User Successfully Logout']);
        }else{
            return response()->json(['status' => 'ERR', 'message' => 'Something went wrong']);
        }
    }

    public function slide(Request $post)
    {
        $output['slides'] = \App\Model\PortalSetting::where('code', 'slides')->get();
        $output['company'] = \App\Model\Company::where('website', $_SERVER['HTTP_HOST'])->first();
        if($output['company']){
            $output['companydata'] = \App\Model\Companydata::where('company_id', $output['company']->id)->first();
        }
        return response()->json($output);
    }

    public function getbalance(Request $post)
    {
        $rules = array(
            'apptoken' => 'required',
            'user_id'  =>'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id',$post->user_id)->first(['mainwallet','aepswallet','microatmbalance','kyc']);
        if($user){
            $output['status']  = "TXN";
            $output['message'] = "Balance Fetched Successfully";
            $output['slides']  = \App\Model\PortalSetting::where('code', 'slides')->get();
            $output['kyc']  = $user->kyc;
            $output['data'] = [ "mainwallet" => $user->mainwallet , "aepswallet" => $user->aepswallet, "microatmbalance" => $user->microatmbalance];
        }else{
            $output['status'] = "ERR";
            $output['message'] = "User details not matched";
        }
        return response()->json($output);
    }

    public function addMember(Request $post)
    {
        $rules = array(
            'user_id'       => 'required',
            'name'       => 'required',
            'mobile'     => 'required|numeric|digits:10|unique:users,mobile',
            'email'      => 'required|email|unique:users,email',
            'shopname'   => 'required|unique:users,shopname',
            'pancard'    => 'required|unique:users,pancard',
            'aadharcard' => 'required|numeric|unique:users,aadharcard|digits:12',
            'state'      => 'required',
            'city'       => 'required',
            'address'    => 'required',
            'pincode'    => 'required|digits:6|numeric',
            'role_id'    => 'required'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $admin = User::where('id', $post->user_id)->first(['id', 'company_id']);

        $post['role_id']    = $post->role_id;
        $post['id']         = "new";
        $post['parent_id']  = $post->user_id;
        $post['password']   = bcrypt('12345678');
        $post['company_id'] = $admin->company_id;
        $post['status']     = "active";
        $post['kyc']        = "verified";

        $maxid = User::max('id');
        $post['agentcode'] = $admin->company->companycode.($maxid+1001);

        $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $post->role_id)->first();
        if($scheme){
            $post['scheme_id'] = $scheme->permission_id;
        }

        $response = User::updateOrCreate(['id'=> $post->id], $post->all());
        if($response){
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $response->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }

            try {
                $content = "Dear Partner, your login details are mobile - ".$post->mobile." & password - ".$post->mobile;
                \Myhelper::sms($post->mobile, $content);

                $otpmailid   = \App\Model\PortalSetting::where('code', 'otpsendmailid')->first();
                $otpmailname = \App\Model\PortalSetting::where('code', 'otpsendmailname')->first();

                $mail = \Myhelper::mail('mail.member', ["username" => $post->mobile, "password" => "12345678", "name" => $post->name], $post->email, $post->name, $otpmailid, $otpmailname, "Member Registration");
            } catch (\Exception $e) {}

            return response()->json(['statuscode' => "TXN", 'message' => "Thank you for choosing, your request is successfully submitted for approval"], 200);
        }else{
            return response()->json(['statuscode' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
    }

    public function profileUpdate(Request $post)
    {
        $rules = [
            'address'=> 'required',
            'city' => 'required',
            'state'  => 'required',
            'pincode'  => 'required',
            'pancard'  => 'required|unique:users,pancard',
            'aadharcard'  => 'required|numeric|digits:12|unique:users,aadharcard',
        ];

        $rules['aadharcardpicfronts'] = 'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024';
        $rules['aadharcardpicbacks'] = 'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024';
        $rules['passbooks'] = 'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024';
        $rules['pancardpics'] = 'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024';
        $rules['profiles'] = 'required|mimes:jpg,JPG,jpeg,png|max:500';

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        $user = User::where('id', $post->user_id)->first();

        try {
            unlink(public_path('kyc/useronboard'.$post->user_id));
        } catch (\Exception $e) {
        }

        if($post->hasFile('aadharcardpicfronts')){
            $post['aadharcardpicfront'] = $post->file('aadharcardpicfronts')->store('kyc/useronboard'.$post->user_id);
        }
        if($post->hasFile('aadharcardpicbacks')){
            $post['aadharcardpicback'] = $post->file('aadharcardpicbacks')->store('kyc/useronboard'.$post->user_id);
        }
        if($post->hasFile('profiles')){
            $post['profile'] = $post->file('profiles')->store('kyc/useronboard'.$post->user_id);
        }
        if($post->hasFile('pancardpics')){
            $post['pancardpic'] = $post->file('pancardpics')->store('kyc/useronboard'.$post->user_id);
        }

        if($post->hasFile('passbooks')){
            $post['passbook'] = $post->file('passbooks')->store('kyc/useronboard'.$post->user_id);
        }
        $post['kyc'] = "submitted";
        $response = User::updateOrCreate(['id'=> $post->user_id], $post->all());
        if($response){
            return response()->json(['statuscode' => "TXN", 'message' => "Your Kyc Successfully Submitted"]);
        }else{
            return response()->json(['statuscode' => 'ERR', 'message' => "Something went wrong, please try again"]);
        }
    }
    
    public function getcommission(Request $post)
    {
        $user = User::where('id', $post->user_id)->first();
        $product = ['mobile', 'dth', 'electricity', 'pancard', 'dmt', 'aeps','matm', 'aadharpay'];
        foreach ($product as $key) {
            $commission = \App\Model\Commission::where('scheme_id', $user->scheme_id)->whereHas('provider', function ($q) use($key){
                $q->where('type' , $key);
            })->get();
            $mydata1 = [];
            foreach ($commission as $commissions){
                $mydata["value"] = $commissions[$user->role->slug];
                $mydata["name"] = $commissions->provider->name;
                $mydata["type"] = $commissions->type;
                
                $mydata1[] = $mydata;
            }
            
            $data[] = $mydata1;
        }
        return response()->json(['status' => "TXN", "key" => $product, "role" => $user->role->slug,"data" => $data]);
    }

    public function changePassword(Request $post)
    {
        $rules = array(
            'oldpassword' =>'required',
            'password'    =>'required|min:8|confirmed',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }

        if(!\Myhelper::can('password_reset', $post->user_id)){
            return response()->json(['status' => "ERR" , "message" => "Permission Not Allowed"]);
        }

        $user = User::where('id', $post->user_id)->first();

        $credentials = [
            'mobile'   => $user->mobile,
            'password' => $post->oldpassword
        ];

        if(!\Auth::validate($credentials)){
            return response()->json(['status' => "ERR" , "message" =>  'Please enter corret old password']);
        }

        $post['password'] = bcrypt($post->password);
        $post['resetpwd'] = "changed";

        $user = User::updateOrCreate(['id'=> $post->user_id], $post->all());

        if($user){
            \LogActivity::addToLog("password-changed-app");
            $output['status']  = "TXN";
            $output['message'] = "Password Changed Successfully";
        }else{
            $output['status']  = "ERR";
            $output['message'] = "Something Went Wrong";
        }
        
        return response()->json($output);
    }

    public function completeKyc(Request $post)
    {
        $rules = array(
            'pancardpics' => 'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024',
            'aadharcardpicfronts'  =>'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024',
            'aadharcardpicbacks'  =>'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024',
            'otherdocs'  =>'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024',
            'profiles'  =>'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024',
            'passbooks'  =>'required|mimes:pdf,jpg,JPG,jpeg,png|max:1024',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        
        $user = User::where('id', $post->user_id)->first();
        if($user->kyc != "verified"){
            try {
                unlink(public_path($user->aadharcardpicfront));
                unlink(public_path($user->aadharcardpicback));
                unlink(public_path($user->pancardpic));
                unlink(public_path($user->passbook));
                unlink(public_path($user->msme));
                unlink(public_path($user->otherdoc));
            } catch (\Exception $e) {
            }

            if($post->hasFile('aadharcardpicfronts')){
                $post['aadharcardpicfront'] = $post->file('aadharcardpicfronts')->store('kyc/useronboard'.$post->user_id);
            }
            if($post->hasFile('aadharcardpicbacks')){
                $post['aadharcardpicback'] = $post->file('aadharcardpicbacks')->store('kyc/useronboard'.$post->user_id);
            }
            if($post->hasFile('pancardpics')){
                $post['pancardpic'] = $post->file('pancardpics')->store('kyc/useronboard'.$post->user_id);
            }
            if($post->hasFile('passbooks')){
                $post['passbook'] = $post->file('passbooks')->store('kyc/useronboard'.$post->user_id);
            }
            if($post->hasFile('profiles')){
                $post['profile'] = $post->file('profiles')->store('kyc/useronboard'.$post->user_id);
            }
            if($post->hasFile('otherdocs')){
                $post['otherdoc'] = $post->file('otherdocs')->store('kyc/useronboard'.$post->user_id);
            }

            $response    = User::updateOrCreate(['id'=> $post->user_id], [
                'aadharcardpicfront' => $post->aadharcardpicfront,
                'aadharcardpicback'  => $post->aadharcardpicback,
                'pancardpic' => $post->pancardpic,
                'passbook'   => $post->passbook,
                'profile'    => $post->profile,
                'otherdoc'   => $post->otherdoc,
                "kyc"     => "submitted",
                "kyctime" => date("Y-m-d H:i:s")
            ]);

            if($response){
                return response()->json(['status' => "TXN", "message" => 'Kyc submitted successfully']);
            }else{
                return response()->json(['status' => "ERR", "message" => 'Something went wrong']);
            }
        }else{
            return response()->json(['status' => 'ERR', 'message' => 'Kyc already submitted']);
        }
    }
}
