<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use App\Model\Api;
use Firebase\JWT\JWT;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function transcode()
    {
        $code = \DB::table('portal_settings')->where('code', 'transactioncode')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return "none";
        }
    }

    public function getAccBalance($id, $wallet)
    {
        $mywallet = \DB::table('users')->where('id', $id)->first([$wallet]);

        $mywallet = (array) $mywallet;
        return $mywallet[$wallet];
    }

    public function pinbased()
    {
        $code = \DB::table('portal_settings')->where('code', 'pincheck')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return "no";
        }
    }

    public function neftcharge()
    {
        $code = \DB::table('portal_settings')->where('code', 'neftcharge')->first(['value']);
        if($code){
           return $code->value;
        }else{
            return 0;
        }
    }

    public function cwbankupdate()
    {   
        $result = \Myhelper::curl("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/v2/details", 'GET', "", [], "no");
        $banks  = json_decode($result['response']);

        foreach ($banks->data as $bank) {
            if($bank->iinno != "NULL" && !is_numeric($bank->bankName)){
                \App\Model\Aepsbank::updateOrCreate(['iinno' => $bank->iinno], [
                    "bankName"  => $bank->bankName,
                    'iinno' => $bank->iinno
                ]);
            }
        }
    }

    public function apbankupdate()
    {   
        $result = \Myhelper::curl("https://fingpayap.tapits.in/fpaepsservice/api/bankdata/bank/details", 'GET', "", [], "no");
        $banks  = json_decode($result['response']);

        foreach ($banks->data as $bank) {
            if($bank->iinno != "NULL"){
                \App\Model\Aepsbank::updateOrCreate(['iinno' => $bank->iinno], [
                    "bankName"  => $bank->bankName,
                    'aadharpay' => $bank->iinno
                ]);
            }
        }
    }
    
    public function pinCheck($data)
    {
        if($this->pinbased() == "yes"){
            if(!\Myhelper::can('pin_check', $data->user_id)){
                $code = \DB::table('pindatas')->where('user_id', $data->user_id)->where('pin', \Myhelper::encrypt($data->pin, "vppay##01012022"))->first();
                if(!$code){
                    return 'fail';
                }
            }
        }
    }
    
    public function raepsthreewayrecon($txnid, $status, $userid)
    {
        $api = Api::where('code', 'paysprint_aeps')->first();
        try {
            $payload =  [
                "timestamp" => time(),
                "partnerId" => $api->username,
                "reqid"     => $userid.Carbon::now()->timestamp
            ];

            $token = JWT::encode($payload, $api->password);
            $url = $api->url."aeps/threeway/threeway";
            $parameter['reference'] = $txnid;
            $parameter['status']    = $status;

            $key = $api->optional2;
            $iv  = $api->optional3;
            $cipher   = openssl_encrypt(json_encode($parameter,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
            $request  = base64_encode($cipher);
            $request  = array('body'=>$request);
            
            $header = array(
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "Token: ".$token,
                "Authorisedkey: ".$api->optional1
            );

            $result = \Myhelper::curl($url, "POST", json_encode($request), $header, "no");
            \DB::table("threewayrecon")->insert([
                "body"     => json_encode($parameter)."/".json_encode($request),
                "header"   => json_encode($header),
                "response" => json_encode($result)
            ]);        
        } catch (\Exception $e) {
            \DB::table('log_500')->insert([
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'log'  => $e->getMessage(),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function threewayreconstore($recon)
    {
        $api = \App\Model\Api::where('code', 'fing_aeps')->first();
        try {
            $setdate = Carbon::createFromFormat('d/m/Y H:i:s', $recon['txndate'])->format('d-m-Y');
            $requestbody =[
                "merchantTransactionId" => $recon['txnid'],
                "fingpayTransactionId"  => $recon['fpTransactionId'],
                "transactionRrn"  => $recon['bankRRN'],
                "responseCode"    => $recon['responseCode'],
                "transactionDate" => $setdate,
                "serviceType"     => $recon['transactionType']
            ];

            $headerbody = json_encode($requestbody).$api->username.$api->optional2;
            $requestheader = [                 
                'txnDate:'.$setdate,   
                'trnTimestamp:'.$recon['txndate'],
                'hash:'.base64_encode(hash("sha256", $headerbody, True)),         
                'superMerchantId:'.$recon['superMerchantId'],
                'superMerchantLoginId:'.$api->username,
                'Content-Type: text/plain'       
            ];

            \DB::table("threewayrecon")->insert([
                "txnid"    => $recon['txnid'],
                "body"     => json_encode($requestbody),
                "header"   => json_encode($requestheader),
                "status"   => "pending",
                "product"  => $recon['product'],
                "via"  => $recon['via'],
                "env"  => $recon['env']
            ]);
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
