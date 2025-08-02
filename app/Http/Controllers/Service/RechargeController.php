<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Provider;
use App\Model\Report;
use App\Model\Api;
use App\User;
use Carbon\Carbon;

class RechargeController extends Controller
{
    public function index($type)
    {
        if (\Myhelper::hasRole('admin') || !\Myhelper::can('recharge_service')) {
            abort(403);
        }
        $data['type'] = $type;
        $data['providers'] = Provider::where('type', $type)->where('status', "1")->get();
        return view('service.recharge')->with($data);
    }

    public function providersList(Request $post)
    {
        $providers = Provider::where('type', $post->type)->where('status', "1")->get(['id','name', 'logo']);
        return response()->json(['statuscode' => "TXN", 'message' => "Provider Fetched Successfully", 'data' => $providers]);
    }

    public function payment(Request $post)
    {
        $rules = [
            'number' => 'required',
            'provider_id' => 'required|numeric',
            'amount'      => 'required|numeric|min:10'
        ];

        if($post->via == "api"){
            $rules["apitxnid"] = "required|unique:reports,apitxnid";
        }

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
                
        if (!\Myhelper::can('recharge_service', $post->user_id)) {
            return response()->json(['statuscode' => "ERR", "message" => "Permission Not Allowed"]);
        }
        
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
            return response()->json(['statuscode' => "ERR", "message" => "Recharge Service Currently Down."]);
        }

        if($user->mainwallet < $post->amount){
            return response()->json(['statuscode' => "ERR", "message" => "Low Balance, Kindly recharge your wallet."]);
        }

        $previousrecharge = Report::where('number', $post->number)->where('provider_id', $post->provider_id)->whereBetween('created_at', [Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])->count();
        if($previousrecharge > 0){
            return response()->json(['statuscode' => "ERR", "message" => 'Same Transaction allowed after 2 min.']);
        }

        do {
            $post['txnid'] = $this->transcode().date('my').rand(111111, 999999);
        } while (Report::where("txnid", "=", $post->txnid)->first() instanceof Report);

        switch ($provider->api->code) {
            case 'ebanker_recharge':
                $url = $provider->api->url."utility/pay";
                $parameter = [
                    "partner_id" => $provider->api->username,
                    "operator"   => $provider->recharge1,
                    "number"     => $post->number,
                    "amount"     => $post->amount,
                    "apitxnid"   => $post->txnid,
                ];

                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "api-key: ".$provider->api->password
                );

                $method = "POST";
                $data["body"] = \Myhelper::ebankerencrypt($parameter, $provider->api->optional1, $provider->api->optional2);
                $query  = json_encode($data);
                break;

            default:
                return response()->json(['statuscode' => "ERR", "message" => "Recharge Service Currently Down."]);
                break;
        }

        $post['profit'] = \Myhelper::getCommission($post->amount, $user->scheme_id, $post->provider_id, $user->role->slug);
        $post['tds']    = ($post->profit * $provider->api->tds)/100;

        $insert = [
            'number' => $post->number,
            'mobile' => $user->mobile,
            'provider_id' => $provider->id,
            'api_id' => $provider->api->id,
            'amount' => $post->amount,
            'profit' => $post->profit,
            'tds'    => $post->tds,
            'txnid'  => $post->txnid,
            'apitxnid'=> $post->apitxnid,
            'status' => 'pending',
            'user_id'=> $user->id,
            'credit_by' => $user->id,
            'rtype'  => 'main',
            'via'    => $post->via,
            'balance'=> $user->mainwallet,
            'trans_type' => 'debit',
            'product'    => 'recharge',
            'create_time'=> $user->id."-".date('ymdhis'),
            "option7" => $post->ip(),
            "option8" => $post->lat."/".$post->lon
        ];

        try {
            $report = \DB::transaction(function () use($insert, $post) {
                $report = Report::create($insert);
                User::where('id', $insert['user_id'])->decrement('mainwallet', $post->amount - ($post->profit - $post->tds));
                return $report;
            });
        } catch (\Exception $e) {
            $report = false;
        }

        if (!$report){
            return response()->json(['statuscode' => "ERR", "message" => 'Transaction Failed, please try again.']);
        }

        try {
            $result = \Myhelper::curl($url, $method, $query, $header, "yes", "report", $post->txnid);
        } catch (\Exception $e) {
            $result = [
                "response" => "",
                "error"    => true
            ];
        }

        if($result['error'] || $result['response'] == ''){
            $update['status'] = "pending";
            $update['payid']  = "pending";
            $update['refno']  = "pending";
        }else{
            switch ($provider->api->code) {
                case 'ebanker_recharge':
                    $doc = json_decode($result['response']);
                    if(isset($doc->statuscode) && in_array($doc->statuscode, ["TXN"])){
                        $update['status'] = "success";
                        $update['refno']  =  $doc->refno;
                        $update['payid']  =  $doc->payid;
                    }elseif(isset($doc->statuscode) && in_array($doc->statuscode, ["ERR", "TXF"])){
                        $update['status'] = "failed";
                        $update['refno'] =  $doc->message;
                        if($doc->message == "Insufficient Wallet Balance"){
                            $update['refno'] =  "Service down for sometime";
                        }
                    }else{
                        $update['status'] = "pending";
                        $update['refno']  = "pending";
                    }
                    break;
            }
        }

        if($update['status'] == "success" || $update['status'] == "pending"){
            Report::where('id', $report->id)->update($update);
            \Myhelper::commission($report);
            $output['statuscode'] = "TXN";
            $output['message'] = "Recharge Accepted";
        }else{
            User::where('id', $user->id)->increment('mainwallet', $post->amount - ($post->profit - $post->tds));
            Report::where('id', $report->id)->update($update);
            $output['statuscode'] = "TXF";
            $output['message'] = $update['refno'];
        }
        
        $output['txnid']   = $post->txnid;
        $output['id']      = $report->id;
        $output['rrn']     = $update['refno'];
        $output['date']    = date("d-M-Y");
        $output['number']  = $report->number;
        $output['provider']= $provider->name;
        $output['status']  = $update['status'];
        $output['amount']  = $post->amount;
        return response()->json($output);
    }

    public function getoperators(Request $post)
    {
        if($post->has("mode")){
            $providers = Provider::where('type', $post->type)->where('mode', $post->mode)->where('status', "1")->get(['id','name']);
        }else{
            $providers = Provider::where('type', $post->type)->where('status', "1")->get(['id','name']);
        }
        return response()->json(['statuscode' => "TXN", "data" => $providers]);
    }

    public function getplan(Request $post)
    {
        $rules = array(
            'provider_id' => 'required|numeric',
            'number'   => 'required|numeric'
        );

        $validate = \Myhelper::FormValidator($rules, $post);
        if($validate != "no"){
            return $validate;
        }
        
        $provider = Provider::where('id', $post->provider_id)->first();
        if(!$provider){
            return response()->json(['statuscode' => "ERR", "message" => "Provider Not Found"]);
        }

        $plan = \DB::table("mplans")->whereDate("updated_at", date('Y-m-d'))->where("provider_id", $provider->id)->first();
        if(!$plan){

            $url = $provider->api->url."utility/recharge/plan";
            $parameter = [
                "partner_id" => $provider->api->username,
                "operator"   => $provider->recharge2,
                "number"     => $post->number,
                "type"       => "plan",
            ];

            $header = array(
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "api-key: ".$provider->api->password
            );

            $method = "POST";
            $data["body"] = \Myhelper::ebankerencrypt($parameter, $provider->api->optional1, $provider->api->optional2);
            $query  = json_encode($data);
            $result = \Myhelper::curl($url, $method, $query, $header, "yes", "Plan", $post->number);

            if($result['response'] == ''){
                return response()->json(['statuscode' => "ERR", "message" => "Something went wrong"]);
            }

            $response = json_decode($result['response'], true);
            if(isset($response['statuscode']) && $response['statuscode'] != "TXN"){
                return response()->json(['statuscode' => "ERR", "message" => "Something went wrong"]);
            }
            
            \DB::table("mplans")->where("provider_id", $provider->id)->delete();
            \DB::table("mplans")->insert([
                "provider_id" => $provider->id,
                "type"     => $provider->type,
                "response" => $result['response'],
                "updated_at" => date("Y-m-d H:i:s")
            ]);

            $plan = \DB::table("mplans")->whereDate("updated_at", date('Y-m-d'))->where("provider_id", $provider->id)->first();
        }

        $response = json_decode($plan->response);
        return response()->json($response);
    }
}
