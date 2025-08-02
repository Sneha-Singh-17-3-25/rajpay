<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Circle;
use App\Model\Role;
use App\Model\Pindata;
use App\Model\Provider;
use App\Model\Report;
use App\Model\NpsAccount;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function routecache(Request $get)
    {
        \Artisan::call("route:cache");
    }

    public function mylogin(Request $get)
    {
        $data['company'] = \App\Model\Company::where('website', $_SERVER['HTTP_HOST'])->first();
        $data['slides']  = \App\Model\PortalSetting::where('code', 'slides')->get();
        $data['state']   = Circle::all();
        $data['roles']   = Role::whereIn('slug', ['whitelable', 'md', 'distributor', 'retailer'])->get();
        return view('login')->with($data);
    }

    public function policy()
    {
        $data['company'] = \App\Model\Company::where('website', $_SERVER['HTTP_HOST'])->first();
        if ($data['company']) {
            $data['companydata'] = \App\Model\Companydata::where('company_id', $data['company']->id)->first();
        }
        return view('policypnd')->with($data);
    }

    // public function login(Request $post)
    // {

    //     $rules = array(
    //         'password' => 'required',
    //     );

    //     $validate = \Myhelper::FormValidator($rules, $post);
    //     if($validate != "no"){
    //         return $validate;
    //     }

    //     try {
    //         $data = \CJS::decrypt($post->password, $post["_token"]);    
    //     } catch (\Exception $e) {
    //         $data = $post;
    //     }

    //     $user = User::where('mobile', $data->mobile)->first();
    //     if(!$user){
    //         return response()->json(['status' => 'ERR', 'message' => "Your aren't registred with us." ]);
    //     }

    //     $company = \App\Model\Company::where('id', $user->company_id)->first();
    //     $otprequired = \App\Model\PortalSetting::where('code', 'otplogin')->first();

    //     if(!\Auth::validate(['mobile' => $data->mobile, 'password' => $data->password])){
    //         return response()->json(['status' => 'ERR', 'message' => 'Username or password is incorrect']);
    //     }

    //     if (!\Auth::validate(['mobile' => $data->mobile, 'password' => $data->password,'status'=> "active"])) {
    //         return response()->json(['status' => 'ERR', 'message' => 'Your account currently de-activated, please contact administrator']);
    //     }

    //     $cookie = "no";

    //     if(isset($_COOKIE["multiauth"])){
    //         $cookieData = \Myhelper::decrypt($_COOKIE["multiauth"], "vppay##01012022");
    //         if($cookieData == $data->mobile){
    //             $cookie = "yes";
    //         }
    //     }

    //     if($otprequired->value == "yes" && $cookie == "no"){
    //         $otp = rand(111111, 999999);
    //         $mydata['otp']    = $otp;
    //         $mydata['mobile'] = $data->mobile;
    //         $mydata['name']   = $user->name;
    //         $mydata['email']  = $user->email;

    //         if($post->has('otp') && $post->otp == "resend"){
    //             if($user->otpresend < 5){
    //                 if($user->otpsendtime < time()- 120){
    //                     $send = \Myhelper::notification("otp", $mydata);
    //                     if($send == 'success'){
    //                         User::where('mobile', $data->mobile)->update([
    //                             'otpverify' => \Myhelper::encrypt($otp, "vppay##01012022"), 
    //                             'otpresend' => $user->otpresend+1,
    //                             'otpsendtime' => time()
    //                         ]);
    //                         return response()->json(['status' => 'TXNOTP', "message" => "Otp Sent Successfully"], 200);
    //                     }else{
    //                         return response()->json(['status' => 'ERR', 'message' => 'Please contact your service provider3']);
    //                     }
    //                 }else{
    //                     return response()->json(['status' => 'ERR', 'message' => 'You can resend otp after 2 min.']);
    //                 }
    //             }else{
    //                 return response()->json(['status' => 'ERR', 'message' => 'Otp resend limit exceed, please contact your service provider']);
    //             }
    //         }

    //         if(!$post->has('otp')){
    //             $send = \Myhelper::notification("otp", $mydata);
    //             if($send == 'success'){
    //                 User::where('mobile', $data->mobile)->update([
    //                     'otpverify' => \Myhelper::encrypt($otp, "vppay##01012022"),
    //                     'otpsendtime' => time()
    //                 ]);
    //                 return response()->json(['status' => 'TXNOTP', "message" => "Otp Sent Successfully"], 200);
    //             }else{
    //                 return response()->json(['status' => 'ERR', 'message' => 'Please contact your service provider1']);
    //             }
    //         }

    //         try {
    //             $otpData = \CJS::decrypt($post->otp, $post["_token"]);   
    //         } catch (\Exception $e) {
    //             return response()->json(['status' => 'ERR', 'message' => 'Please contact your service provider2']);
    //         }

    //         if (\Auth::attempt(['mobile' => $data->mobile, 'password' =>$data->password, 'otpverify' => \Myhelper::encrypt($otpData->otp, "vppay##01012022"), 'status'=>"active"])){
    //             User::where('mobile', $post->mobile)->update(['otpverify' => "yes"]);
    //             session(['loginid' => $user->id]);
    //             $expire = time() + (60 * 60 * 24 * 7); 
    //             setcookie("multiauth", \Myhelper::encrypt($data->mobile, "vppay##01012022"), $expire, "/", "", 1, true);
    //             \App\Model\Loginsession::create([
    //                 'user_id'    => $user->id,
    //                 'ip_address' => $post->ip(),
    //                 'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    //                 'gps_location' => $data->gps_location
    //             ]);
    //             return response()->json(['status' => 'TXN'], 200);
    //         }else{
    //             return response()->json(['status' => 'ERR', 'message' => 'Please provide correct otp']);
    //         }
    //     }else{
    //         if (\Auth::attempt(['mobile' =>$data->mobile, 'password' =>$data->password, 'status'=> "active"])) {
    //             \App\Model\Loginsession::create([
    //                 'user_id'    => $user->id,
    //                 'ip_address' => $post->ip(),
    //                 'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    //                 'gps_location' => $data->gps_location
    //             ]);
    //             session(['loginid' => $user->id]);
    //             return response()->json(['status' => 'TXN'], 200);
    //         }else{
    //             return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, please contact administrator']);
    //         }
    //     }
    // }
    public function login(Request $post)
    {

        $rules = array(
            'password' => 'required',
            'mobile' => 'required', // Add mobile validation
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if ($validate != "no") {
            return $validate;
        }

        try {
            $data = \CJS::decrypt($post->password, $post["_token"]);
        } catch (\Exception $e) {
            $data = $post;
        }


        // Make sure we have mobile field
        $mobile = isset($data->mobile) ? $data->mobile : $post->mobile;
        $password = isset($data->password) ? $data->password : $post->password;

        // Check if user exists
        $user = User::where('mobile', $mobile)->first();
        if (!$user) {
            return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us."]);
        }

        $company = \App\Model\Company::where('id', $user->company_id)->first();
        $otprequired = \App\Model\PortalSetting::where('code', 'otplogin')->first();

        // Single authentication check with proper credentials
        if (!\Auth::validate(['mobile' => $mobile, 'password' => $password])) {
            return response()->json(['status' => 'ERR', 'message' => 'Username or password is incorrect']);
        }

        // Check if user is active - separate check after authentication
        if ($user->status !== 'active') {
            return response()->json(['status' => 'ERR', 'message' => 'Your account currently de-activated, please contact administrator']);
        }

        $cookie = "no";

        if (isset($_COOKIE["multiauth"])) {
            $cookieData = \Myhelper::decrypt($_COOKIE["multiauth"], "vppay##01012022");
            if ($cookieData == $mobile) {
                $cookie = "yes";
            }
        }

        if ($otprequired->value == "yes" && $cookie == "no") {
            $otp = rand(111111, 999999);
            $mydata['otp']    = $otp;
            $mydata['mobile'] = $mobile;
            $mydata['name']   = $user->name;
            $mydata['email']  = $user->email;

            if ($post->has('otp') && $post->otp == "resend") {
                if ($user->otpresend < 5) {
                    if ($user->otpsendtime < time() - 120) {
                        $send = \Myhelper::notification("otp", $mydata);
                        if ($send == 'success') {
                            User::where('mobile', $mobile)->update([
                                'otpverify' => \Myhelper::encrypt($otp, "vppay##01012022"),
                                'otpresend' => $user->otpresend + 1,
                                'otpsendtime' => time()
                            ]);
                            return response()->json(['status' => 'TXNOTP', "message" => "Otp Sent Successfully"], 200);
                        } else {
                            return response()->json(['status' => 'ERR', 'message' => 'Please contact your service provider3']);
                        }
                    } else {
                        return response()->json(['status' => 'ERR', 'message' => 'You can resend otp after 2 min.']);
                    }
                } else {
                    return response()->json(['status' => 'ERR', 'message' => 'Otp resend limit exceed, please contact your service provider']);
                }
            }

            if (!$post->has('otp')) {
                $send = \Myhelper::notification("otp", $mydata);
                if ($send == 'success') {
                    User::where('mobile', $mobile)->update([
                        'otpverify' => \Myhelper::encrypt($otp, "vppay##01012022"),
                        'otpsendtime' => time()
                    ]);
                    return response()->json(['status' => 'TXNOTP', "message" => "Otp Sent Successfully"], 200);
                } else {
                    return response()->json(['status' => 'ERR', 'message' => 'Please contact your service provider1']);
                }
            }

            try {
                $otpData = \CJS::decrypt($post->otp, $post["_token"]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERR', 'message' => 'Please contact your service provider2']);
            }

            if (\Auth::attempt(['mobile' => $mobile, 'password' => $password, 'otpverify' => \Myhelper::encrypt($otpData->otp, "vppay##01012022"), 'status' => "active"])) {
                User::where('mobile', $mobile)->update(['otpverify' => "yes"]);
                session(['loginid' => $user->id]);
                $expire = time() + (60 * 60 * 24 * 7);
                setcookie("multiauth", \Myhelper::encrypt($mobile, "vppay##01012022"), $expire, "/", "", 1, true);
                \App\Model\Loginsession::create([
                    'user_id'    => $user->id,
                    'ip_address' => $post->ip(),
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'gps_location' => isset($data->gps_location) ? $data->gps_location : null
                ]);
                return response()->json(['status' => 'TXN'], 200);
            } else {
                return response()->json(['status' => 'ERR', 'message' => 'Please provide correct otp']);
            }
        } else {
            if (\Auth::attempt(['mobile' => $mobile, 'password' => $password, 'status' => "active"])) {
                \App\Model\Loginsession::create([
                    'user_id'    => $user->id,
                    'ip_address' => $post->ip(),
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'gps_location' => isset($data->gps_location) ? $data->gps_location : null
                ]);
                session(['loginid' => $user->id]);
                // return response()->json(['status' => 'TXN'], 200);
                return redirect()->route('home'); // Redirect to dashboard instead of JSON response
            } else {
                return response()->json(['status' => 'ERR', 'message' => 'Something went wrong, please contact administrator']);
            }
        }
    }

    public function logout(Request $request)
    {
        \Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    public function passwordReset(Request $post)
    {
        $rules = array(
            'type'    => 'required',
            'mobile'  => 'required|numeric',
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if ($validate != "no") {
            return $validate;
        }

        if ($post->type == "request") {
            $user = \App\User::where('mobile', $post->mobile)->first();
            if ($user) {
                $otp = rand(111111, 999999);
                $mydata['otp']    = $otp;
                $mydata['mobile'] = $post->mobile;
                $mydata['name']   = $user->name;
                $mydata['email']  = $user->email;
                $send = \Myhelper::notification("password", $mydata);

                if ($send == "success") {
                    \DB::table('password_resets')->insert([
                        'mobile' => $post->mobile,
                        'token' => $otp,
                        'ip'    => $post->ip(),
                        'useragent' => $_SERVER['HTTP_USER_AGENT'],
                        "last_activity" => time()
                    ]);

                    return response()->json(['status' => 'TXN', 'message' => "Password reset token sent successfully"]);
                } else {
                    return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
                }
            } else {
                return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"]);
            }
        } else {
            $user = \DB::table('password_resets')->where('mobile', $post->mobile)->where('token', $post->token)->first();
            if ($user) {
                $update = \App\User::where('mobile', $post->mobile)->update(['password' => bcrypt($post->password)]);
                if ($update) {
                    \DB::table('password_resets')->where('mobile', $post->mobile)->where('token', $post->token)->delete();
                    return response()->json(['status' => "TXN", 'message' => "Password reset successfully"], 200);
                } else {
                    return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
                }
            } else {
                return response()->json(['status' => 'ERR', 'message' => "Please enter valid token"]);
            }
        }
    }

    public function registration(Request $post)
    {
        $rules = array(
            'name'       => 'required',
            'mobile'     => 'required|numeric|digits:10|unique:users,mobile',
            'email'      => 'required|email|unique:users,email',
            'shopname'   => 'required|unique:users,shopname',
            'pancard'    => 'required|unique:users,pancard',
            'aadharcard' => 'required|numeric|unique:users,aadharcard|digits:12',
            'state'      => 'required',
            'city'       => 'required',
            'address'    => 'required',
            'pincode'    => 'required|digits:6|numeric'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if ($validate != "no") {
            return $validate;
        }

        $admin = User::whereHas('role', function ($q) {
            $q->where('slug', 'admin');
        })->first(['id', 'company_id']);

        $role = Role::where('slug', 'retailer')->first();
        $post['role_id']    = $role->id;
        $post['id']         = "new";
        $post['parent_id']  = $admin->id;
        $post['password']   = bcrypt($post->mobile);
        $post['company_id'] = $admin->company_id;
        $post['status']     = "block";
        $post['kyc']        = "pending";
        $post['type']       = "web";

        $maxid = User::max('id');
        $post['agentcode'] = $admin->company->companycode . ($maxid + 1001);

        $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $role->id)->first();
        if ($scheme) {
            $post['scheme_id'] = $scheme->permission_id;
        }

        $response = User::updateOrCreate(['id' => $post->id], $post->all());
        if ($response) {
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if (sizeof($permissions) > 0) {
                foreach ($permissions as $permission) {
                    $insert = array('user_id' => $response->id, 'permission_id' => $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }


            $mydata['mobile'] = $response->mobile;
            $mydata['name']   = $response->name;
            $mydata['email']  = $response->email;
            $send = \Myhelper::notification("id", $mydata);

            return response()->json(['status' => "TXN", 'message' => "Member Registered Successfully"], 200);
        } else {
            return response()->json(['status' => 'ERR', 'message' => "Something went wrong, please try again"], 400);
        }
    }

    //     public function getotp(Request $post)
    //     {
    //         $rules = array(
    //             'mobile'  =>'required|numeric',
    //         );

    //         $validate = \Myhelper::FormValidator($rules, $post);
    //         if($validate != "no"){
    //             return $validate;
    //         }

    //         $user = \App\User::where('mobile', $post->mobile)->first();
    //         if($user){
    //             $otp = rand(111111, 999999);
    //             $mydata['otp']    = $otp;
    //             $mydata['mobile'] = $post->mobile;
    //             $mydata['name']   = $user->name;
    //             $mydata['email']  = $user->email;
    //             $send = \Myhelper::notification("tpin", $mydata);

    //             if($send == "success"){
    //                 $user = \DB::table('password_resets')->insert([
    //                     'mobile' => $post->mobile,
    //                     'token' => \Myhelper::encrypt($otp, "vppay##01012022"),
    //                     'last_activity' => time()
    //                 ]);

    //                 return response()->json(['status' => 'TXN', 'message' => "Pin generate token sent successfully"], 200);
    //             }else{
    //                 return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
    //             }
    //         }else{
    //             return response()->json(['status' => 'ERR', 'message' => "You aren't registered with us"]);
    //         }  
    //     }

    public function setpin(Request $post)
    {
        $rules = array(
            'mobile'  => 'required|numeric',
            'otp' => 'sometimes|required|numeric',
            'pin' => [
                'required',
                'numeric',
                'digits:4',
                'confirmed',
                Rule::notIn(['1234']),
            ]
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if ($validate != "no") {
            return $validate;
        }

        $user = \DB::table('password_resets')->where('mobile', $post->mobile)->where('token', \Myhelper::encrypt($post->otp, "vppay##01012022"))->first();
        if ($user || \Myhelper::hasRole("admin")) {
            try {
                Pindata::where('user_id', $post->user_id)->delete();
                $apptoken = Pindata::create([
                    'pin' => \Myhelper::encrypt($post->pin, "vppay##01012022"),
                    'user_id'  => $post->user_id
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'ERR', 'message' => 'Try Again']);
            }

            if ($apptoken) {
                if ($user) {
                    \DB::table('password_resets')->where('mobile', $post->mobile)->where('token', \Myhelper::encrypt($post->otp, "vppay##01012022"))->delete();
                }
                return response()->json(['status' => "TXN"]);
            } else {
                return response()->json(['status' => 'ERR', 'message' => "Something went wrong"]);
            }
        } else {
            return response()->json(['status' => 'ERR', 'message' => "Please enter valid otp"]);
        }
    }
}
