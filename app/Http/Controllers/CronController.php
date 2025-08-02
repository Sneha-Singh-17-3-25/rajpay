<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Report;
use App\User;
use Carbon\Carbon;

class CronController extends Controller
{
	public function sessionClear(){
	    \DB::table('sessions')->where('last_activity' , '<', time()- 3600)->delete();
  	}

	public function checkcron(){
	    \DB::table('micrologs')->insert(['response' => "asdff", 'product' => "thuritj"]);
  	}

  	public function pancardClear(Request $get){
    	if($get->data == "all"){
        	$reports = \DB::table("reports")->where('product', 'directpancard')->whereIn('status', ['refund_pending'])->where('created_at', "<=", Carbon::now()->subMinutes(120)->format('Y-m-d H:i:s'))->take(200)->get(['id', 'txnid', 'payid', 'remark', 'created_at', 'refno']);
        }elseif($get->data == "allnsdl"){
        	$reports = \DB::table("reports")->where('product', 'nsdl')->whereIn('status', ['pending'])->whereDate('created_at', $get->date)->take(200)->get(['id', 'txnid', 'payid', 'remark', 'created_at', 'refno']);
        // 	dd($reports, $get->date);
        }else{
        	$reports = \DB::table("reports")->where('product', 'directpancard')->where('txnid', $get->data)->whereIn('status', ['refund_pending'])->where('created_at', "<=", Carbon::now()->subMinutes(120)->format('Y-m-d H:i:s'))->take(200)->get(['id', 'txnid', 'payid', 'remark', 'refno', 'created_at']);
        }
    
    // 	dd($reports, Carbon::now()->subMinutes(120));
    	
    // 	die("Unable to process");
  		foreach ($reports as $report) {
  			$checkSumData = array(            
                "MERCHANTCODE" => "MODISH2022",
                "REQUESTID"    => $report->txnid,
                "SSOTOKEN"     => "0"
            );
            $checkSumData_query = "toBeCheckSumString=".json_encode($checkSumData);

            $header = [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ];

            $checkSumurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum";
            $checkSumcurl = \Myhelper::curl($checkSumurl, "POST", $checkSumData_query, $header, "no");

            if($checkSumcurl["response"] != ""){
                if($report->refno == ""){
                    $refno = "Timeout";
                }else{
                    $refno = $report->refno;
                }
                
                $failedData = array(            
                    "MERCHANTCODE" => "MODISH2022",
                    "SSOTOKEN"     => "0",
                    "REQUESTID"    => $report->txnid,
                    "EMITRATOKEN"  => $report->payid,
                    "CHECKSUM"     => $checkSumcurl["response"],
                    "ENTITYTYPEID" => "2",
                    "CANCELREMARK" => $refno
                );

                $header = [
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ];

                $dataForEncryption = "toBeEncrypt=".json_encode($failedData);

                $encurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption";
                $enccurl = \Myhelper::curl($encurl, "POST", $dataForEncryption, $header, "no");

                $backToCancleEncryption = "encData=".$enccurl["response"];
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
                if(isset($cancleData->CANCELSTATUS) && $cancleData->CANCELSTATUS == "SUCCESS"){
                    Report::where('id', $report->id)->update([
                        "status" => "refunded"
                    ]);
                }
            }
  		}
        return redirect()->back();
  	}

  	public function emitraRefund(Request $get)
  	{
  		$checkSumData = array(            
            "MERCHANTCODE" => "MODISH2022",
            "REQUESTID"    => $get->txnid,
            "SSOTOKEN"     => "0"
        );
        $checkSumData_query = "toBeCheckSumString=".json_encode($checkSumData);

        $header = [
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ];

        $checkSumurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum";
        $checkSumcurl = \Myhelper::curl($checkSumurl, "POST", $checkSumData_query, $header, "no");

        if($checkSumcurl["response"] != ""){
            $failedData = array(            
                "MERCHANTCODE" => "MODISH2022",
                "SSOTOKEN"     => "0",
                "REQUESTID"    => $get->txnid,
                "EMITRATOKEN"  => $get->payid,
                "CHECKSUM"     => $checkSumcurl["response"],
                "ENTITYTYPEID" => "2",
                "CANCELREMARK" => "Timeout"
            );

            $header = [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ];

            $dataForEncryption = "toBeEncrypt=".json_encode($failedData);

            $encurl  = "https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption";
            $enccurl = \Myhelper::curl($encurl, "POST", $dataForEncryption, $header, "no");

            $backToCancleEncryption = "encData=".$enccurl["response"];
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
            dd($checkSumData, $failedData, $checkSumcurl["response"], $cancleData);
        }
  	}

  	public function pancardStatus(){
  		$reports = \DB::table("reports")->where('product', 'directpancard')->whereIn('status', ['pending'])->where('created_at', "<=", Carbon::now()->subMinutes(120)->format('Y-m-d H:i:s'))->take(200)->get(['id', 'txnid', 'payid', 'remark']);

  		foreach ($reports as $report) {
  			$parameterData = array(
	            "txnReqEntityData"=> array(
	                "request_type"    => "T",
	                "txn_id"          => $report->txnid,
	                "unique_txn_id"   => $report->txnid,
	                "date"            => "",
	                "entity_Id"       => "AchariyaTechno",
	                "dscProvider"     => "Verasys CA 2014",
	                "dscSerialNumber" => "2B 32 60 77",
	                "dscExpiryDate"   => "4-3-24",
	                "returnUrl" => url("callback/nsdlpan"),
	                "reqTs"     => date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes")),
	                "authKey"   => "AchariyaTechno",
	            ),
	            "signature"=>""
	        );
        
	       // $url = "https://admin.e-banker.in/api/nsdl/signature?type=T&data=".base64_encode(json_encode($parameterData));
	       // $signature = \Myhelper::curl($url, "POST", "", [], "no");
	       // $data      = substr(json_decode(json_encode($signature["response"]), true), 52);
            // dd($data);
             $base64Request = base64_encode(json_encode($parameterData));

    //             // Sign the request using Java utility
                $certPath = public_path('YESPAL.pfx');
                $certPassword = env('CERT_PASSWORD', '123456789');
                $jarPath = public_path('Assissted_Signing_v3.jar');
                $alias = 'yespal singh'; // Certificate alias
                $flag = 'T';

    //             // Prepare Java command for signing
                $command = "java -jar \"$jarPath\" \"$base64Request\" \"$certPath\" \"$certPassword\" \"$alias\" \"$flag\" 2>&1";

    //             // Execute signing command
                $signedRequest = shell_exec($command);
                
                 $startPos = strpos($signedRequest, '{');
                $endPos = strrpos($signedRequest, '}');
                
                $fullResponse = substr($signedRequest, $startPos, $endPos - $startPos + 1);
                $data = $fullResponse;
                // dd($fullResponse);
            
	        $url = "https://assisted-service.egov.proteantech.in/SpringBootFormHandling/checkTransactionStatus";
	        $response = \Myhelper::curl($url, "POST", $data, array(
	            "cache-control: no-cache",
	            "content-type: application/json",
	            "postman-token: 9a6f7632-b4ea-78f1-c393-12a31b12316a"
	          ), "no");
	       //   
	       //   dd($response);

	        $pandata = json_decode($response["response"]);
        // dd($pandata);
	        if(isset($pandata->status) && $pandata->status == "success"){
	        	\DB::table("reports")->where("id", $report->id)->update(["status" => "success", "refno" => $pandata->ack_No]);
	        }elseif(isset($pandata->status) && $pandata->status == "incomplete"){
	        	\DB::table("reports")->where("id", $report->id)->update(["status" => "refund_pending", "refno" => "Application is incomplete"]);
	        }elseif(isset($pandata->status) && $pandata->status == "failure"){
	        	\DB::table("reports")->where("id", $report->id)->update(["status" => "refund_pending", "refno" => $pandata->errordesc]);
	        }elseif(isset($pandata->error) && $pandata->error->ER066 == "Transaction ID not present in database. Kindly retry with correct Transaction ID."){
	           
	           if(isset($report->id))
	           {
	               // dd($report->id);
	               
            	$update = \DB::table("reports")->where("id", $report->id)->update(["status" => "refund_pending", "refno" => $pandata->error->ER066]);
            // 	dd($update);
	           }
            }
  		}
  		return redirect()->back(); // 

  	}
}
