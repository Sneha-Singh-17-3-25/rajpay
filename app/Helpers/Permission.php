<?php
namespace App\Helpers;
 
use Illuminate\Http\Request;
use App\Model\Aepsreport;
use App\Model\UserPermission;
use App\Model\Apilog;
use App\Model\Scheme;
use App\Model\Commission;
use App\User;
use App\Model\Report;
use App\Model\Utiid;
use App\Model\Provider;
use App\Model\Packagecommission;
use App\Model\Microatmreport;
use App\Model\Package;
use App\Model\Callbackresponse;

class Permission {
    /**
     * @param String $permissions
     * 
     * @return boolean
     */
    public static function can($permission , $id="none") {
        if($id == "none"){
            $id = \Auth::id();
        }
        $user = User::where('id', $id)->first();

        if(is_array($permission)){
            $mypermissions = \DB::table('permissions')->whereIn('slug' ,$permission)->get(['id'])->toArray();
            if($mypermissions){
                foreach ($mypermissions as $value) {
                    $mypermissionss[] = $value->id;
                }
            }else{
                $mypermissionss = [];
            }
            $output = UserPermission::where('user_id', $id)->whereIn('permission_id', $mypermissionss)->count();
        }else{
            $mypermission = \DB::table('permissions')->where('slug' ,$permission)->first(['id']);
            if($mypermission){
                $output = UserPermission::where('user_id', $id)->where('permission_id', $mypermission->id)->count();
            }else{
                $output = 0;
            }
        }

        if($output > 0 || $user->role->slug == "admin"){
            return true;
        }else{
            return false;
        }
    }

    public static function hasRole($roles , $id="none") {
        if($id == "none"){
            $id = \Auth::id();
        }

        $user = User::where('id', $id)->first();
        if(is_array($roles)){
            if(in_array($user->role->slug, $roles)){
                return true;
            }else{
                return false;
            }
        }else{
            if($user->role->slug == $roles){
                return true;
            }else{
                return false;
            }
        }
    }

    public static function hasNotRole($roles, $id="none") {
        if($id == "none"){
            $id = \Auth::id();
        }

        $user = User::where('id', $id)->first();
        if(is_array($roles)){
            if(!in_array($user->role->slug, $roles)){
                return true;
            }else{
                return false;
            }
        }else{
            if($user->role->slug != $roles){
                return true;
            }else{
                return false;
            }
        }
    }

    public static function apiLog($url, $modal, $txnid, $header, $request, $response)
    {
        try {
            $apiresponse = Apilog::create([
                "url" => $url,
                "modal" => $modal,
                "txnid" => $txnid,
                "header" => $header,
                "request" => $request,
                "response" => $response
            ]);
        } catch (\Exception $e) {
            $apiresponse = "error";
        }
        return $apiresponse;
    }

    public static function mail($view, $data, $mailto, $name, $mailvia, $namevia, $subject)
    {
        \Mail::send($view, $data, function($message) use($mailto, $name, $mailvia, $namevia, $subject) {
            $message->to($mailto, $name)->subject($subject);
            $message->from($mailvia, $namevia);
        });

        if (\Mail::failures()) {
            return "fail";
        }
        return "success";
    }

    public static function notification($product, $data)
    {
        $otpmailid   = \App\Model\PortalSetting::where('code', 'otpsendmailid')->first();
        $otpmailname = \App\Model\PortalSetting::where('code', 'otpsendmailname')->first();

        switch ($product) {
            case 'otp':
                $content = "Your login OTP is ".$data["otp"]." from support PND DIGITAL PRIVATE LIMITED";
                $send = \Myhelper::sms($data['mobile'], $content, "1707164163091046449");

                try {
                    \Myhelper::mail('mail.otp', ["name" => $data['name'], "otp" => $data["otp"], "type" => "Login"], $data["email"], $data['name'], $otpmailid->value, $otpmailname->value, "Otp Login");
                } catch (\Exception $e) {}
                break;

            case 'tpin':
                $content = "Your login OTP is ".$data["otp"]." from support PND DIGITAL PRIVATE LIMITED";
                $send = \Myhelper::sms($data['mobile'], $content, "1707164163091046449");

                try {
                    \Myhelper::mail('mail.otp', ["name" => $data['name'], "otp" => $data["otp"], "type" => "T-Pin"], $data["email"], $data['name'], $otpmailid->value, $otpmailname->value, "T-Pin Reset");
                } catch (\Exception $e) {}
                break;
            
            case 'password':
                $content = "Your login OTP is ".$data["otp"]." from support PND DIGITAL PRIVATE LIMITED";
                $send = \Myhelper::sms($data['mobile'], $content, "1707164163091046449");
                try {
                    \Myhelper::mail('mail.otp', ["name" => $data['name'], "otp" => $data["otp"], "type" => "Password Reset"], $data["email"], $data['name'], $otpmailid->value, $otpmailname->value, "Password Reset");
                } catch (\Exception $e) {}
                break;
            
            case 'id':
                $content = "Congratulation! You are Registered with Your User ID is ".$data['mobile']." , Your Password is ".$data['mobile']." and Pin is 0000. PND DIGITAL PRIVATE LIMITED.";
                $send = \Myhelper::sms($data['mobile'], $content, "1707164163019233123");
                try {
                    \Myhelper::mail('mail.member', ["username" => $data['mobile'], "password" => $data["mobile"], "name" => $data['name']], $data["email"], $data['name'], $otpmailid->value, $otpmailname->value, "Member Registration");
                } catch (\Exception $e) {}
                break;

            default:
                return "fail";
                break;
        }

        return $send;
    }

    public static function sms($mobile, $content, $variables)
    {
        $smsdata = \App\Model\Company::where('website', $_SERVER['HTTP_HOST'])->first();

        $smsapi = \App\Model\Api::where("code", "smsapi")->first();
        if($smsapi && $smsapi->status == "1"){
            $url = "https://priority.muzztech.in/sms_api/sendsms.php?username=".$smsapi->username."&password=".$smsapi->password."&mobile=".$mobile."&sendername=".$smsapi->optional1."&message=".urlencode($content)."&templateid=".$variables;

            $url = "http://bulksms.saakshisoftware.in/api/mt/SendSMS?user=".$smsapi->username."&password=".$smsapi->password."&senderid=".$smsapi->optional1."&channel=transactional&DCS=0&flashsms=0&number=".$mobile."&text=".urlencode($content)."&route=4";

            $result = \Myhelper::curl($url, "GET", "", [], "yes", "Mobile", $mobile);
            if($result['response'] != ''){
                if(str_contains($result["response"], "Job Id")){
                    return "success";
                }
            }
        }

        return "fail";
    }

    public static function commission($report)
    {
        $insert = [
            'number' => $report->number,
            'mobile' => $report->mobile,
            'provider_id' => $report->provider_id,
            'api_id' => $report->api_id,
            'txnid'  => $report->txnid,
            'payid'  => $report->payid,
            'refno'  => $report->refno,
            'status' => 'success',
            'rtype'  => 'commission',
            'via'    => $report->via,
            'trans_type' => "credit",
            'product' => $report->product
        ];
        if($report->product == "dmt"){
            $precommission = $report->charge - $report->profit - $report->gst;
        }elseif($report->option1 == "M"){
            $precommission = $report->charge;
        }else{
            $precommission = $report->profit;
        }
        $provider = $report->provider_id;
    

        $parent = User::where('id', $report->user->parent_id)->first(['id', 'mainwallet', 'scheme_id', 'role_id', 'parent_id']);

        if($parent && $parent->role->slug == "distributor"){
            $insert['balance'] = $parent->mainwallet;
            $insert['user_id'] = $parent->id;
            $insert['credit_by'] = $report->user_id;
            
            if($report->product == "expdmt"){
                $amount = $report->amount;
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

                $parentcommission = 0;
                foreach ($amounts as $amount) {
                    if($amount >= 100 && $amount <= 1000){
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

                    $parentcommission += \Myhelper::getCommission($amount, $parent->scheme_id, $provider->id, 'distributor');
                }
            }elseif($report->product == "dmt"){
                $amount = $report->amount;
                if($amount >= 100 && $amount <= 1000){
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

                $parentcommission = \Myhelper::getCommission($amount, $parent->scheme_id, $provider->id, 'distributor');
            }else{
                $parentcommission = \Myhelper::getCommission($report->amount, $parent->scheme_id, $provider, 'distributor');
            }

            if($report->product == "dmt" || $report->product == "expdmt" || $report->option1 == 'M'){
                $insert['amount'] = $precommission - $parentcommission;
            }elseif($report->product == "pancard"){
                $insert['amount'] = ($report->option1 * $parentcommission) - $precommission;
            }else{
                $insert['amount'] = $parentcommission - $precommission;
            }

            if($insert['amount'] > 0){
                User::where('id', $parent->id)->increment('mainwallet', $insert['amount']);
                Report::create($insert);
            }

            if(in_array($report->product, ['aeps', 'matm'])){
                Aepsreport::where('id', $report->id)->update(['disid' => $parent->id, "disprofit" => $insert['amount']]);
            }else{
                Report::where('id', $report->id)->update(['disid' => $parent->id, "disprofit" => $insert['amount']]);
            }
            if($report->product == "pancard"){
                $precommission = $report->option1 * $parentcommission;
            }else{
                $precommission = $parentcommission;
            }
            $parent = User::where('id', $parent->parent_id)->first(['id', 'mainwallet', 'scheme_id', 'role_id']);
        }
        
        if($parent && $parent->role->slug == "md"){
            $insert['balance'] = $parent->mainwallet;
            $insert['user_id'] = $parent->id;
            $insert['credit_by'] = $report->user_id;

            if($report->product == "expdmt"){
                $amount = $report->amount;
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

                $parentcommission = 0;
                foreach ($amounts as $amount) {
                    if($amount >= 100 && $amount <= 1000){
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

                    $parentcommission += \Myhelper::getCommission($amount, $parent->scheme_id, $provider->id, 'md');
                }
            }elseif($report->product == "dmt"){
                $amount = $report->amount;
                if($amount >= 100 && $amount <= 1000){
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

                $parentcommission = \Myhelper::getCommission($amount, $parent->scheme_id, $provider->id, 'md');
            }else{
                $parentcommission = \Myhelper::getCommission($report->amount, $parent->scheme_id, $provider, 'md');
            }

            if($report->product == "expdmt" ||$report->product == "dmt" || $report->option1 == 'M'){
                $insert['amount'] = $precommission - $parentcommission;
            }elseif($report->product == "pancard"){
                $insert['amount'] = ($report->option1 * $parentcommission) - $precommission;
            }else{
                $insert['amount'] = $parentcommission - $precommission;
            }

            if($insert['amount'] > 0){
                User::where('id', $parent->id)->increment('mainwallet', $insert['amount']);
                Report::create($insert);
            }
            
            if(in_array($report->product, ['aeps', 'matm'])){
                Aepsreport::where('id', $report->id)->update(['mdid' => $parent->id, "mdprofit" => $insert['amount']]);
            }else{
                Report::where('id', $report->id)->update(['mdid' => $parent->id, "mdprofit" => $insert['amount']]);
            }
            if($report->product == "pancard"){
                $precommission = $report->option1 * $parentcommission;
            }else{
                $precommission = $parentcommission;
            }
            $parent = User::where('id', $parent->parent_id)->first(['id', 'mainwallet', 'scheme_id', 'role_id', 'parent_id']);
        }

        if($parent && $parent->role->slug == "whitelable"){
            $insert['balance'] = $parent->mainwallet;
            $insert['user_id'] = $parent->id;
            $insert['credit_by'] = $report->user_id;

            if($report->product == "expdmt"){
                $amount = $report->amount;
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

                $parentcommission = 0;
                foreach ($amounts as $amount) {
                    if($amount >= 100 && $amount <= 1000){
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

                    $parentcommission += \Myhelper::getCommission($amount, $parent->scheme_id, $provider->id, 'whitelable');
                }
            }elseif($report->product == "dmt"){
                $amount = $report->amount;
                if($amount >= 100 && $amount <= 1000){
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

                $parentcommission = \Myhelper::getCommission($amount, $parent->scheme_id, $provider->id, 'whitelable');
            }else{
                $parentcommission = \Myhelper::getCommission($report->amount, $parent->scheme_id, $provider, 'whitelable');
            }

            if($report->product == "expdmt" ||$report->product == "dmt" || $report->option1 == 'M'){
                $insert['amount'] = $precommission - $parentcommission;
            }elseif($report->product == "pancard"){
                $insert['amount'] = ($report->option1 * $parentcommission) - $precommission;
            }else{
                $insert['amount'] = $parentcommission - $precommission;
            }

            if($insert['amount'] > 0){
                User::where('id', $parent->id)->increment('mainwallet', $insert['amount']);
                Report::create($insert);
            }
            
            if(in_array($report->product, ['aeps', 'matm'])){
                Aepsreport::where('id', $report->id)->update(['wid' => $parent->id, "wprofit" => $insert['amount']]);
            }else{
                Report::where('id', $report->id)->update(['wid' => $parent->id, "wprofit" => $insert['amount']]);
            }
        }
    }

    public static function getCommission($amount, $scheme, $slab, $role)
    {
        $myscheme = Scheme::where('id', $scheme)->first(['status']);
        if($myscheme && $myscheme->status == "1"){
            $comdata = Commission::where('scheme_id', $scheme)->where('slab', $slab)->first();
            if ($comdata) {
                    if ($comdata->type == "percent") {
                        $commission = $amount * $comdata[$role] / 100;
                    }else{
                        $commission = $comdata[$role];
                    }
                if($commission == null){
                    $commission = 0;
                }
            }else{
                $commission = 0;
            }
        }else{
            $commission = 0;
        }
        return $commission;
    }

    public static function curl($url , $method='GET', $parameters, $header, $log="no", $modal="none", $txnid="none", $port="")
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_TIMEOUT, 240);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        if($parameters != ""){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        }

        if(sizeof($header) > 0){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        if($port != ""){
            curl_setopt($curl, CURLOPT_PORT, $port);
        }
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($log != "no"){
            Apilog::create([
                "url" => $url,
                "modal" => $modal,
                "txnid" => $txnid,
                "header" => $header,
                "request" => $parameters,
                "response" => $response
            ]);
        }

        return ["response" => $response, "error" => $err, 'code' => $code];
    }

    public static function getParents($id)
    {
        $data = [];
        $user = User::where('id', $id)->first(['id', 'role_id']);
        if($user){
            $data[] = $id;
            switch ($user->role->slug) {
                case 'admin':
                    $whitelabels = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'whitelable');
                    })->get(['id']);

                    if(sizeOf($whitelabels) > 0){
                        foreach ($whitelabels as $value) {
                            $data[] = $value->id;
                        }
                    }

                    $mds = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'md');
                    })->get(['id']);

                    if(sizeOf($mds) > 0){
                        foreach ($mds as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $distributors = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'distributor');
                    })->get(['id']);

                    if(sizeOf($distributors) > 0){
                        foreach ($distributors as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $retailers = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->whereIn('slug', ['retailer', 'apiuser', 'retaillite']);
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
                    
                case 'whitelable':
                    $mds = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'md');
                    })->get(['id']);

                    if(sizeOf($mds) > 0){
                        foreach ($mds as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $distributors = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'distributor');
                    })->get(['id']);

                    if(sizeOf($distributors) > 0){
                        foreach ($distributors as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $retailers = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->whereIn('slug', ['retailer']);
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
                
                case 'md':                
                    $distributors = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->where('slug', 'distributor');
                    })->get(['id']);

                    if(sizeOf($distributors) > 0){
                        foreach ($distributors as $value) {
                            $data[] = $value->id;
                        }
                    }
                    
                    $retailers = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->whereIn('slug', ['retailer', 'retaillite']);
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
                
                case 'distributor':                
                    $retailers = \App\User::whereIn('parent_id', $data)->whereHas('role', function($q){
                        $q->whereIn('slug', ['retailer']);
                    })->get(['id']);

                    if(sizeOf($retailers) > 0){
                        foreach ($retailers as $value) {
                            $data[] = $value->id;
                        }
                    }
                    break;
            }
        }
        return $data;
    }
    
    public static function transactionRefund($id, $product = "reports")
    {
        $report = \DB::table($product)->where('id', $id)->first();
        $count  = \DB::table($product)->where('user_id', $report->user_id)->where('status', 'refunded')->where('txnid', $report->id)->count();
        
        if($count == 0){
            $user   = User::where('id', $report->user_id)->first(['id', 'mainwallet']);
            $amount = $report->amount + ($report->charge + $report->gst) - ($report->profit - $report->tds);
            $insert = [
                'number'   => $report->number,
                'mobile'   => $report->mobile,
                'provider_id' => $report->provider_id,
                'api_id'   => $report->api_id,
                'apitxnid' => $report->apitxnid,
                'txnid'    => $report->txnid,
                'payid'    => $report->payid,
                'refno'    => $report->refno,
                'description' => "Transaction Reversed, amount refunded",
                'remark'   => $report->remark,
                'option1'  => $report->option1,
                'option2'  => $report->option2,
                'option3'  => $report->option3,
                'option4'  => $report->option3,
                'status'   => 'refunded',
                'rtype'    => $report->rtype,
                'via'      => $report->via,
                'trans_type' => ($report->trans_type == "credit") ? "debit" : "credit",
                'product'  => $report->product,
                'amount'   => $report->amount,
                'profit'   => $report->profit,
                'charge'   => $report->charge,
                'gst'      => $report->gst,
                'tds'      => $report->tds,
                'balance'  => $user->mainwallet,
                'user_id'  => $report->user_id,
                'credit_by'   => $report->credit_by
            ];

            try {
                $refundReport = \DB::transaction(function () use($insert, $amount) {
                    if($insert['trans_type'] == "credit"){
                        User::where('id', $insert['user_id'])->increment('mainwallet', $amount);
                    }else{
                        User::where('id', $insert['user_id'])->decrement('mainwallet', $amount);
                    }
                    return Report::create($insert);
                });
            } catch (\Exception $e) {
                \DB::table('log_500')->insert([
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'log'  => $e->getMessage(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                \DB::table($product)->where('id', $id)->update(["status" => "pending"]);
                $refundReport = false;
            }

            try {
                if($refundReport){
                    $commissionReports = \DB::table($product)->where('rtype', 'commission')->where('status', 'success')->where('txnid', $report->txnid)->where("product", $report->product)->get();

                    foreach ($commissionReports as $commissionReport) {
                        $user = User::where('id', $commissionReport->user_id)->first(['id', 'mainwallet', 'role_id']);
                        $insert = [
                            'number'  => $commissionReport->number,
                            'mobile'  => $commissionReport->mobile,
                            'provider_id' => $commissionReport->provider_id,
                            'api_id'  => $commissionReport->api_id,
                            'apitxnid'=> $commissionReport->apitxnid,
                            'txnid'   => $report->txnid,
                            'payid'   => $commissionReport->payid,
                            'refno'   => $commissionReport->refno,
                            'description' => "Transaction Reversed, amount refunded",
                            'remark'  => $commissionReport->remark,
                            'option1' => $commissionReport->option1,
                            'option2' => $commissionReport->option2,
                            'option3' => $commissionReport->option3,
                            'option4' => $commissionReport->option3,
                            'status'  => 'refunded',
                            'rtype'   => $commissionReport->rtype,
                            'via'     => $commissionReport->via,
                            'trans_type' => ($commissionReport->trans_type == "credit") ? "debit" : "credit",
                            'product' => $commissionReport->product,
                            'amount'  => $commissionReport->amount,
                            'profit'  => $commissionReport->profit,
                            'charge'  => $commissionReport->charge,
                            'gst'     => $commissionReport->gst,
                            'tds'     => $commissionReport->tds,
                            'balance' => $user->mainwallet,
                            'user_id'     => $commissionReport->user_id,
                            'credit_by'   => $commissionReport->credit_by
                        ];

                        try {
                            \DB::transaction(function () use($insert, $product, $commissionReport, $refundReport, $user) {
                                if($insert['trans_type'] == "credit"){
                                    User::where('id', $insert["user_id"])->increment('mainwallet', ($insert['amount'] - $insert['tds']));
                                }else{
                                    User::where('id', $insert["user_id"])->decrement('mainwallet', ($insert['amount'] - $insert['tds']));
                                }
                                Report::create($insert);
                                \DB::table($product)->where('id', $commissionReport->id)->update(["status" => "reversed"]);

                                if($user->role->slug == "distributor"){
                                    \DB::table("reports")->where('id', $refundReport->id)->update(["disid" => $insert['user_id'], "disprofit" => $insert['amount']]);
                                }

                                if($user->role->slug == "md"){
                                    \DB::table("reports")->where('id', $refundReport->id)->update(["mdid" => $insert['user_id'], "mdprofit" => $insert['amount']]);
                                }

                                if($user->role->slug == "whitelable"){
                                    \DB::table("reports")->where('id', $refundReport->id)->update(["wid" => $insert['user_id'], "wprofit" => $insert['amount']]);
                                }
                            });
                        } catch (\Exception $e) {
                            \DB::table('log_500')->insert([
                                'line' => $e->getLine(),
                                'file' => $e->getFile(),
                                'log'  => $e->getMessage(),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);

                            \DB::table("commission_refunds")->insert([
                                "product"   => $report->product,
                                "txnid"     => $report->txnid,
                                "user_id"   => $user->id,
                                "refund_id" => $refundReport->id,
                                "amount"    => $insert['amount'] - $insert['tds'],
                                "created_at" => date("Y-m-d H:i:s")
                            ]);
                        }
                    }
                }    
            } catch (\Exception $e) {
                \DB::table('log_500')->insert([
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'log'  => $e->getMessage(),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    public static function transactionRefundAeps($id)
    {
        $report = \DB::table("aepsreports")->where('id', $id)->first();
        $count  = \DB::table("aepsreports")->where('user_id', $report->user_id)->where('status', 'refunded')->where('txnid', $report->id)->count();

        if($count == 0){
            $mywallet = \App\User::where('id', $report->user_id)->select(["aepswallet"])->first();
            $aepsreports['api_id'] = $report->api_id;
            $aepsreports['provider_id'] = $report->provider_id;
            $aepsreports['payid']  = $report->payid;
            $aepsreports['mobile'] = $report->mobile;
            $aepsreports['refno']  = $report->refno;
            $aepsreports['number'] = $report->number;
            $aepsreports['amount'] = $report->amount;
            $aepsreports['charge'] = $report->charge;
            $aepsreports['profit'] = $report->profit;
            $aepsreports['tds']    = $report->tds;
            $aepsreports['txnid']  = $report->id;
            $aepsreports['user_id']= $report->user_id;
            $aepsreports['credit_by']  = $report->credit_by;
            $aepsreports['balance']    = $mywallet["aepswallet"];
            $aepsreports['trans_type'] = "credit";
            $aepsreports['option1']   = $report->option1;
            $aepsreports['option2']   = $report->option2;
            $aepsreports['option3']   = $report->option3;
            $aepsreports['option4']   = $report->option4;
            $aepsreports['option5']   = $report->option5;
            $aepsreports['option6']   = $report->option6;
            $aepsreports['option7']   = $report->option7;
            $aepsreports['option8']   = $report->option8;
            $aepsreports['product']   = $report->product;
            $aepsreports['via']   = $report->via;
            $aepsreports['status'] = 'refunded';
            $aepsreports['remark'] = "Bank Settlement";
            Aepsreport::create($aepsreports);
            User::where('id', $aepsreports['user_id'])->increment("aepswallet", $aepsreports['amount'] + $aepsreports['charge']);
        }
    }

    public static function getTds($amount)
    {
        return $amount*5/100;
    }

    public static function callback($id, $product)
    {
        switch ($product) {
            case 'recharge':
                $report = Report::where('id', $id)->first();
                $callback['product'] = $product;
                $callback['status']  = $report->status;
                $callback['refno']   = $report->refno;
                $callback['txnid']   = $report->apitxnid;
                $query = http_build_query($callback);
                $url = $report->user->callbackurl."?".$query;

                $result = \Myhelper::curl($url, "GET", "", [], "no", "", "");
                Callbackresponse::create([
                    'url' => $url,
                    'response' => ($result['response'] != '') ? $result['response'] : $result['error'],
                    'status'   => $result['code'],
                    'product'  => $product,
                    'user_id'  => $report->user_id,
                    'transaction_id' => $report->id
                ]);
                break;
        }
    }

    public static function FormValidator($rules, $post)
    {
        $validator = \Validator::make($post->all(), array_reverse($rules));
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error = $value[0];
            }
            return response()->json(array(
                'statuscode' => 'ERR',
                'message'    => $error
            ));
        }else{
            return "no";
        }
    }
    
    public static  function encrypt($plainText, $key)
    {
        $secretKey = \Myhelper::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $secretKey, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }
    
    public static function decrypt($encryptedText, $key) {
        $key = \Myhelper::hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = \Myhelper::hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    public static  function hextobin($hexString) {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }
    
            $count += 2;
        }
        return $binString;
    }

    public static function ebankerencrypt($data, $key, $iv)
    {
        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        $ciphertext_raw = openssl_encrypt($data, "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv);
        return bin2hex($ciphertext_raw);
    }
}