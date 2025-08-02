<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Report;
use App\Model\Utiid;
use App\Model\Aepsreport;
use App\Model\Aepsfundrequest;
use App\Model\Microatmfundrequest;
use App\Model\Microatmreport;
use App\Model\Api;
use App\User;
use App\Model\Aepsuser;
use MiladRahimi\Jwt\Generator;
use MiladRahimi\Jwt\Parser;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use Firebase\JWT\JWT;

class CallbackController extends Controller
{

    public function paysprintOnboard(Request $post){
        \DB::table('microlog')->insert(["product" => $post->event, 'response' => json_encode($post->all()), 'created_at' => date('Y-m-d H:i:s')]);
        $api = Api::where('code', 'raeps')->first();
        
        if(isset($post->event)){
            switch ($post->event) {
                case 'MERCHANT_ONBOARDING':
                    if(isset($post->param['merchant_id'])){
                        $action = Aepsuser::where('merchantLoginId', $post->param['merchant_id'])->update([
                            'merchantLoginPin' => $post->param['request_id'],
                            'status' => 'pending'
                        ]);
                    }
                    break;
    
                case 'MERCHANT_STATUS_ONBOARD':
                    if($post->has("param_enc")){
                        $claims = JWT::decode($post->param_enc, $api->password, array('HS256'));
                        if(isset($claims->status) && $claims->status == 1){
                            $action = Aepsuser::where('merchantLoginId', $claims->merchantcode)->update([
                                'status' => 'approved'
                            ]);
                            
                            if($action){
                                return response()->json(["status"=> 200,"message"=>"Transaction completed successfully"]);
                            }else{
                                return response()->json(['status' => 400, 'message' => "Something went wrong"]);
                            }
                        }
                    }
                    break;
    
                case 'RECHARGE_SUCCESS':
                case 'BILLPAY_SUCCESS' :
                    if(isset($post->param['referenceid'])){
                        $action = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->update([
                            'refno'  => $post->param['operatorid'],
                            'status' => 'success'
                        ]);
                    }
                    break;
    
                case 'RECHARGE_FAILURE':
                case 'BILLPAY_FAILURE':
                    if(isset($post->param['referenceid'])){
                        $report = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->first();
                        if($report){
                            $action = Report::where('id', $report->id)->update([
                                'refno'  => $post->param['message'],
                                'status' => 'reversed'
                            ]);
    
                            if($action){
                                \Myhelper::transactionRefund($report->id);
                            }
                        }
                    }
                    break;
    
                case 'DMT':
                    if(isset($post->param['referenceid'])){
                        $report = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->first();
                        if($report){
                            switch ($post->param['txn_status']) {
                                case 'FAILED':
                                    $action = Report::where('id', $report->id)->update([
                                        'refno'  => $post->param['message'],
                                        'status' => 'reversed'
                                    ]);
                                    if($action){
                                        \Myhelper::transactionRefund($report->id);
                                    }
                                    break;
                                
                                default:
                                    $action = Report::where('txnid', $post->param['referenceid'])->whereIn('status', ['success', 'pending'])->update([
                                        'refno'  => $post->param['operatorid'],
                                        'status' => 'success'
                                    ]);
                                    break;
                            }
                        }
                    }
                    break;
    
                case 'PAYOUT_SETTLEMENT':
                    if($post->has("param_inc")){
                        $claims = JWT::decode($post->param_inc, $api->password, array('HS256'));
                        
                        if(isset($claims->status) && $claims->status == 1){
                            $action = Aepsfundrequest::where('payoutid', $claims->refid)->update([
                                'status'    => 'approved',
                                'payoutref' => $claims->utr
                            ]);
                            
                            if($action){
                                return response()->json(["status"=> 200,"message"=>"Transaction completed successfully"]);
                            }else{
                                return response()->json(['status' => 400, 'message' => "Something went wrong"]);
                            }
                        }
                    }
                    break;
                
                default:
                    break;
            }
        }else{
            if($post->has("data")){
                $claims = JWT::decode($post->data, $api->password, array('HS256'));
                if(isset($claims->status) && $claims->status == 1){
                    $action = Aepsuser::where('merchantLoginId', $claims->merchantcode)->update([
                        'status' => 'approved'
                    ]);
                    
                    return redirect(url('aeps/initiate'));
                }
            }
        }
        return response()->json(["status"=> 200,"message"=>"Transaction completed successfully"]);
    }

    public function payu(Request $post){
        \DB::table('microlog')->insert(["product" => 'payupayout', 'response' => json_encode($post->all())]);

        if(str_contains($post->merchantReferenceId, "DMT")){
            $reportupdate = Report::where('txnid', $post->merchantReferenceId)->whereIn("status", ["success", "pending"])->first();
            
            if ($reportupdate) {
                switch (strtolower($post->event)) {
                    case 'transfer_reversed':
                    case 'transfer_failed':
                        $update = Report::where('id', $reportupdate->id)->update(['status' => "reversed", "refno" => $post->msg]);

                        if($update){
                            \Myhelper::transactionRefund($reportupdate->id);
                        }
                        break;

                    case 'transfer_success':
                        Report::where('id', $reportupdate->id)->update(['status' => "success", "refno" => $post->bankReferenceId]);
                        break;
                }
            }
        }else{
            $reportupdate = Aepsfundrequest::where('payoutid', $post->merchantReferenceId)->whereIn("status", ["approved", "pending"])->first();
            
            if ($reportupdate) {
                switch (strtolower($post->event)) {
                    case 'transfer_reversed':
                    case 'transfer_failed':
                        $update = Aepsfundrequest::where('id', $reportupdate->id)->update(['status' => "rejected", "payoutref" => $post->msg]);

                        if($update){
                            Aepsreport::where('txnid', $reportupdate->payoutid)->update(['status' => "reversed"]);
                            $aepsreport = Aepsreport::where('txnid', $reportupdate->payoutid)->first();
                            $aepsreports['api_id'] = $aepsreport->api_id;
                            $aepsreports['payid']  = $aepsreport->payid;
                            $aepsreports['mobile'] = $aepsreport->mobile;
                            $aepsreports['refno']  = $aepsreport->refno;
                            $aepsreports['aadhar'] = $aepsreport->aadhar;
                            $aepsreports['amount'] = $aepsreport->amount;
                            $aepsreports['charge'] = $aepsreport->charge;
                            $aepsreports['bank']   = $aepsreport->bank;
                            $aepsreports['txnid']  = $aepsreport->id;
                            $aepsreports['user_id']= $aepsreport->user_id;
                            $aepsreports['credited_by'] = $aepsreport->credited_by;
                            $aepsreports['balance']     = $aepsreport->user->aepswallet;
                            $aepsreports['type']        = "credit";
                            $aepsreports['transtype']   = 'fund';
                            $aepsreports['status'] = 'refunded';
                            $aepsreports['remark'] = "Bank Settlement Refunded";

                            User::where('id', $aepsreports['user_id'])->increment('aepswallet', $aepsreports['amount'] + $aepsreports['charge']);
                            Aepsreport::create($aepsreports);
                        }
                        break;

                    case 'transfer_success':
                        Aepsfundrequest::where('id', $reportupdate->id)->update(['status' => "approved", "payoutref" => $post->bankReferenceId]);
                        break;
                }
            }
        }

        return response()->json(['status' => "200", "message" => "Transaction completed successfully"]);
    }

    public function razor(Request $post){
        \DB::table('microlog')->insert(["product" => 'razor', 'response' => json_encode($post->all())]);
        $data = json_decode($post->all(), true);

        if(str_contains($data['payload']['payout']['entity']['reference_id'], "DMT")){
            $reportupdate = Report::where('txnid', $data['payload']['payout']['entity']['reference_id'])->whereIn("status", ["success", "pending"])->first();
            
            if ($reportupdate) {
                switch (strtolower($post->event)) {
                    case 'payout.reversed':
                    case 'payout.rejected':
                    case 'payout.failed':
                        $update = Report::where('id', $reportupdate->id)->update(['status' => "reversed", "refno" => $data['payload']['payout']['entity']['status_details']["description"]]);

                        if($update){
                            \Myhelper::transactionRefund($reportupdate->id);
                        }
                        break;

                    case 'payout.processed':
                        Report::where('id', $reportupdate->id)->update(['status' => "success", "refno" => $data['payload']['payout']['entity']['utr']]);
                        break;
                }
            }
        }else{

            $reportupdate = Aepsfundrequest::where('payoutid', $data['payload']['payout']['entity']['reference_id'])->whereIn("status", ["approved", "pending"])->first();
            
            if ($reportupdate) {
                switch (strtolower($post->event)) {
                    case 'payout.reversed':
                    case 'payout.rejected':
                    case 'payout.failed':
                        $update = Aepsfundrequest::where('id', $reportupdate->id)->update(['status' => "rejected", "payoutref" => $data['payload']['payout']['entity']['status_details']["description"]]);

                        if($update){
                            Aepsreport::where('txnid', $reportupdate->payoutid)->update(['status' => "reversed"]);
                            $aepsreport = Aepsreport::where('txnid', $reportupdate->payoutid)->first();
                            $aepsreports['api_id'] = $aepsreport->api_id;
                            $aepsreports['payid']  = $aepsreport->payid;
                            $aepsreports['mobile'] = $aepsreport->mobile;
                            $aepsreports['refno']  = $aepsreport->refno;
                            $aepsreports['aadhar'] = $aepsreport->aadhar;
                            $aepsreports['amount'] = $aepsreport->amount;
                            $aepsreports['charge'] = $aepsreport->charge;
                            $aepsreports['bank']   = $aepsreport->bank;
                            $aepsreports['txnid']  = $aepsreport->id;
                            $aepsreports['user_id']= $aepsreport->user_id;
                            $aepsreports['credited_by'] = $aepsreport->credited_by;
                            $aepsreports['balance']     = $aepsreport->user->aepswallet;
                            $aepsreports['type']        = "credit";
                            $aepsreports['transtype']   = 'fund';
                            $aepsreports['status'] = 'refunded';
                            $aepsreports['remark'] = "Bank Settlement Refunded";

                            User::where('id', $aepsreports['user_id'])->increment('aepswallet', $aepsreports['amount'] + $aepsreports['charge']);
                            Aepsreport::create($aepsreports);
                        }
                        break;

                    case 'payout.processed':
                        Aepsfundrequest::where('id', $reportupdate->id)->update(['status' => "approved", "payoutref" => $data['payload']['payout']['entity']['utr']]);
                        break;
                }
            }
        }

        return response()->json(['status' => "200", "message" => "Transaction completed successfully"]);
    }
    
    public function cashfree(Request $post){
        \DB::table('microlog')->insert(["product" => 'cashfree', 'response' => json_encode($post->all())]);
        return response()->json(['status' => "200", "message" => "Transaction completed successfully"]);
    }
}
