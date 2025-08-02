<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Exports\ReportExport;
use App\User;
use DB;

class ExportController extends Controller
{
    public $admin;
    public function __construct()
    {
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();
    }

    public function export(Request $request, $type)
    {
        if (\Myhelper::hasNotRole("admin")) {
            abort(403);
        }

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600);
        $data = [];
        if($request->has('user_id')){
            $userid = $request->user_id;
        }else{
            $userid = \Auth::id();
        }

        switch ($type) {
            case 'aeps':
            case 'aadharpay':
            case 'ministatement':
            case 'matm':
            case 'payout':
                $tables = "aepsreports";
                $query  = \DB::table($tables)->leftJoin('users', 'users.id', '=', $tables.'.user_id')
                            ->leftJoin('users as distributor', 'distributor.id', '=', $tables.'.disid')
                            ->leftJoin('users as md', 'md.id', '=', $tables.'.mdid')
                            ->leftJoin('users as whitelable', 'whitelable.id', '=', $tables.'.wid')
                        ->leftJoin('apis', 'apis.id', '=', $tables.'.api_id')
                        ->leftJoin('providers', 'providers.id', '=', $tables.'.provider_id')
                        ->orderBy($tables.'.id', 'desc')
                        ->where($tables.'.rtype', "main");

                switch ($request->type) {
                    case 'aeps':
                        $query->whereIn($tables.'.option1', ['CW'])->where($tables.".product", "aeps");
                        break;

                    case 'aadharpay':
                        $query->whereIn($tables.'.option1', ['M'])->where($tables.".product", "aeps");
                        break;

                    case 'ministatement':
                        $query->whereIn($tables.'.option1', ['MS'])->where($tables.".product", "aeps");
                        break;

                    case 'payout':
                        $query->whereIn($tables.'.option1', ['bank', 'wallet'])->where($tables.'.product', 'payout');
                        break;

                    case 'matm':
                        $query->where($tables.'.option1', ["CW"])->where($tables.".product", "matm");
                        break;
                }

                if(!empty($request->agent) && (\Myhelper::hasRole("admin", $userid) || in_array($request->agent, \Myhelper::getParents($userid)))){
                    $query->where($tables.'.user_id', $request->agent);
                }else{
                    if (\Myhelper::hasRole(['retailer'], $userid)){
                        $query->where($tables.'.user_id', $userid);
                    }elseif(\Myhelper::hasRole(['md', 'distributor','whitelable'], $userid)){
                        $query->whereIn($tables.'.user_id', \Myhelper::getParents($userid));
                    }
                }

                $dateFilter = 1;
                if(!empty($request->searchtext)){
                    $serachDatas = ['number', 'txnid', 'payid', 'refno', 'id'];
                    $query->where( function($q) use($request, $serachDatas, $tables){
                        foreach ($serachDatas as $value) {
                            $q->orWhere($tables.".".$value , 'like', '%'.$request->searchtext.'%');
                        }
                    });
                    $dateFilter = 0;
                }

                if(isset($request->product) && !empty($request->product)){
                    $query->where($tables.'.api_id', $request->product);
                    $dateFilter = 0;
                }
                
                if(isset($request->status) && $request->status != '' && $request->status != null){
                    $query->where($tables.'.status', $request->status);
                    $dateFilter = 0;
                }

                if((isset($request->fromdate) && !empty($request->fromdate)) && (isset($request->todate) && !empty($request->todate))){
                    if($request->fromdate == $request->todate){
                        $query->whereDate($tables.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                    }else{
                        $query->whereBetween($tables.'.created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);
                    }
                }elseif($dateFilter && isset($request->fromdate) && !empty($request->fromdate)){
                    $query->whereDate($tables.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                }

                $selects = [
                    $tables.'.id', 
                    'providers.name as providername',
                    $tables.'.number', 
                    $tables.'.mobile', 
                    $tables.'.txnid' , 
                    $tables.'.payid' , 
                    $tables.'.refno' , 
                    $tables.'.amount',
                    $tables.'.charge', 
                    $tables.'.profit', 
                    $tables.'.tds'   , 
                    $tables.'.status', 
                    $tables.'.trans_type', 
                    $tables.'.option1', 
                    $tables.'.user_id', 
                    'users.name as username', 
                    'users.mobile as usermobile', 
                    $tables.'.remark', 
                    $tables.'.created_at'
                ];

                $selects[] = $tables.'.api_id';
                $selects[] = 'apis.name as apiname';

                if($type == "payout"){
                    $selects[] = $tables.'.description'; 
                    $selects[] = $tables.'.number as account'; 
                    $selects[] = $tables.'.option3'; 
                    $selects[] = $tables.'.option2'; 
                }

                if (\Myhelper::hasRole(['admin', 'distributor', 'md', 'whitelable'], $userid)){
                    $selects[] = "distributor.name as distributorname";
                    $selects[] = "distributor.mobile as distributormobile";
                    $selects[] = $tables.'.disprofit';
                }

                if (\Myhelper::hasRole(['admin', 'md', 'whitelable'], $userid)){
                    $selects[] = "md.name as mdname";
                    $selects[] = "md.mobile as mdmobile";
                    $selects[] = $tables.'.mdprofit';
                }

                if (\Myhelper::hasRole(['admin', 'whitelable'], $userid)){
                    $selects[] = "whitelable.name as whitelablename";
                    $selects[] = "whitelable.mobile as whitelablemobile";
                    $selects[] = $tables.'.wprofit'; 
                }
                
                $titles = [
                    'Id', 
                    'Provider',
                    'Number',
                    'Mobile', 
                    'Txnid', 
                    'Payid', 
                    'Refno', 
                    'Amount', 
                    'Charge',
                    'Profit', 
                    'Tds', 
                    'Status',  
                    'Type',
                    'Product',
                    'Agent Id', 
                    'Agent Name', 
                    'Agent Mobile' , 
                    'Remark',
                    'Craete Time',
                ];

                $titles[] = "Api Id";
                $titles[] = 'Api Name';

                if($type == "payout"){
                    $titles[] = "Account Holder Name";
                    $titles[] = 'Bank Account';
                    $titles[] = 'Bank Name';
                    $titles[] = 'Bank Ifsc';
                }
            
                if (\Myhelper::hasRole(['admin', 'distributor', 'md', 'whitelable'], $userid)){
                    $titles[] = "Distributor Name";
                    $titles[] = "Distributor Mobile";
                    $titles[] = "Distributor Profit";
                }
                
                if (\Myhelper::hasRole(['admin', 'md', 'whitelable'], $userid)){
                    $titles[] = "MD Name";
                    $titles[] = "MD Mobile";
                    $titles[] = "MD Profit";
                }
                
                if (\Myhelper::hasRole(['admin', 'whitelable'], $userid)){
                    $titles[] = "Whitelable Name";
                    $titles[] = "Whitelable Mobile";
                    $titles[] = "Whitelable Profit";
                }

                $exportData = $query->select($selects)->get()->toArray();
                break;

            case 'pancard':
            case 'billpay':
            case 'recharge':
            case 'offlinebillpay':
            case 'dmt':
        	case 'directpancard':
    	    case 'nsdlaccount':
                $tables = "reports";
                $query  = \DB::table($tables)->leftJoin('users', 'users.id', '=', $tables.'.user_id')
                            ->leftJoin('users as distributor', 'distributor.id', '=', $tables.'.disid')
                            ->leftJoin('users as md', 'md.id', '=', $tables.'.mdid')
                            ->leftJoin('users as whitelable', 'whitelable.id', '=', $tables.'.wid')
                        ->leftJoin('apis', 'apis.id', '=', $tables.'.api_id')
                        ->leftJoin('providers', 'providers.id', '=', $tables.'.provider_id')
                        ->orderBy($tables.'.id', 'desc')
                        ->where($tables.'.rtype', "main");

                switch ($request->type) {
                    case 'recharge':
                        $query->where($tables.'.product', 'recharge');
                        break;
                    
                    case 'billpay':
                        $query->where($tables.'.product', 'billpay');
                        break;
                    
                    case 'offlinebillpay':
                        $query->where($tables.'.product', 'offlinebillpay');
                        break;

                    case 'pancard':
                        $query->where($tables.'.product', 'pancard');
                        break;
                    
                    case 'dmt':
                        $query->where($tables.'.product', 'dmt');
                        break;
                
                	case 'directpancard':
                        $query->where($tables.'.product', 'directpancard');
                        break;
                        
                    case 'nsdlaccount':
                        $query->where($tables.'.product', 'nsdl');
                        break;
                }

                if(!empty($request->agent) && (\Myhelper::hasRole("admin", $userid) || in_array($request->agent, \Myhelper::getParents($userid)))){
                    $query->where($tables.'.user_id', $request->agent);
                }else{
                    if (\Myhelper::hasRole(['retailer'], $userid)){
                        $query->where($tables.'.user_id', $userid);
                    }elseif(\Myhelper::hasRole(['md', 'distributor','whitelable'], $userid)){
                        $query->whereIn($tables.'.user_id', \Myhelper::getParents($userid));
                    }
                }

                $dateFilter = 1;
                if(!empty($request->searchtext)){
                    $serachDatas = ['number', 'txnid', 'payid', 'refno', 'id'];
                    $query->where( function($q) use($request, $serachDatas, $tables){
                        foreach ($serachDatas as $value) {
                            $q->orWhere($tables.".".$value , 'like', '%'.$request->searchtext.'%');
                        }
                    });
                    $dateFilter = 0;
                }

                if(isset($request->product) && !empty($request->product)){
                    $query->where($tables.'.api_id', $request->product);
                    $dateFilter = 0;
                }
                
                if(isset($request->status) && $request->status != '' && $request->status != null){
                    $query->where($tables.'.status', $request->status);
                    $dateFilter = 0;
                }

                if((isset($request->fromdate) && !empty($request->fromdate)) && (isset($request->todate) && !empty($request->todate))){
                    if($request->fromdate == $request->todate){
                        $query->whereDate($tables.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                    }else{
                        $query->whereBetween($tables.'.created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);
                    }
                }elseif($dateFilter && isset($request->fromdate) && !empty($request->fromdate)){
                    $query->whereDate($tables.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                }
        		
				if($type == "directpancard" || $type == "nsdlaccount"){
                	$selects = [
                    	$tables.'.id', 
                    	'providers.name as providername',
                    	$tables.'.mobile',
                    	$tables.'.option4',
                    	$tables.'.txnid' , 
                    	$tables.'.payid' , 
                    	$tables.'.refno' , 
                    	$tables.'.amount',
                    	$tables.'.charge', 
                    	$tables.'.profit', 
                    	$tables.'.tds'   , 
                    	$tables.'.status',
                    	$tables.'.product',
                    	$tables.'.remark', 
                    	$tables.'.created_at'
                	];
                }else{
                	$selects = [
                    	$tables.'.id', 
                    	'providers.name as providername',
                    	$tables.'.mobile', 
                    	$tables.'.number', 
                    	$tables.'.txnid' , 
                    	$tables.'.payid' , 
                    	$tables.'.refno' , 
                    	$tables.'.amount',
                    	$tables.'.charge', 
                    	$tables.'.profit', 
                    	$tables.'.tds'   , 
                    	$tables.'.status',
                    	$tables.'.product', 
                    	$tables.'.user_id', 
                    	'users.name as username', 
                    	'users.mobile as usermobile', 
                    	$tables.'.remark', 
                    	$tables.'.created_at' 
                	];
                
                	$selects[] = $tables.'.api_id';
                	$selects[] = 'apis.name as apiname';
                }
        		
                if($type == "pancard"){
                    $selects[] = $tables.'.option1';
                }
                
                if($type == "directpancard"){
                	$selects[] = $tables.'.option6';
                }
                
                if($type == "nsdlaccount"){
                	$selects[] = $tables.'.option7';
                }
        
        		if($type == "directpancard" || $type == "nsdlaccount"){
                	$selects[] = DB::raw("json_extract(option10, '$.SSOID')");
                	$selects[] = DB::raw("json_extract(option10, '$.KIOSKCODE')");
                	$selects[] = DB::raw("json_extract(option10, '$.KIOSKNAME')");
                	$selects[] = DB::raw("json_extract(option10, '$.TEHSIL')");
                	$selects[] = DB::raw("json_extract(option10, '$.MOBILE')");
                	$selects[] = DB::raw("json_extract(option10, '$.EMAIL')");
                	$selects[] = DB::raw("json_extract(option10, '$.DISTRICT')");
                }

                if($type == "offlinebillpay" || $type == "billpay"){
                    $selects[] = $tables.'.option1';
                    $selects[] = $tables.'.option2';
                }

                if (\Myhelper::hasRole(['admin', 'distributor', 'md', 'whitelable'], $userid)){
                    $selects[] = "distributor.name as distributorname";
                    $selects[] = "distributor.mobile as distributormobile";
                    $selects[] = $tables.'.disprofit';
                }

                if (\Myhelper::hasRole(['admin', 'md', 'whitelable'], $userid)){
                    $selects[] = "md.name as mdname";
                    $selects[] = "md.mobile as mdmobile";
                    $selects[] = $tables.'.mdprofit';
                }

                if (\Myhelper::hasRole(['admin', 'whitelable'], $userid)){
                    $selects[] = "whitelable.name as whitelablename";
                    $selects[] = "whitelable.mobile as whitelablemobile";
                    $selects[] = $tables.'.wprofit'; 
                }
                if($type == "directpancard" || $type == "nsdlaccount"){
               		$titles = [
                    	'Id', 
                    	'Provider',
                    	'Mobile',
                    	'Customer Name',
                    	'Request Id', 
                    	'Payid', 
                    	'Refno', 
                    	'Amount', 
                    	'Charge',
                    	'Profit', 
                    	'Tds', 
                    	'Status',
                    	'Product',
                    	'Remark',
                    	'Create Time'
                	];
                }else{
                	$titles = [
                    	'Id', 
                    	'Provider',
                    	'Number',
                    	'Mobile', 
                    	'Txnid', 
                    	'Payid', 
                    	'Refno', 
                    	'Amount', 
                    	'Charge',
                    	'Profit', 
                    	'Tds', 
                    	'Status',  
                    	'Type',
                    	'Product',
                    	'Agent Id', 
                    	'Agent Name', 
                    	'Agent Mobile' , 
                    	'Remark',
                    	'Create Time',
                	];
                
                	$titles[] = "Api Id";
                	$titles[] = 'Api Name';
                }

                if($type == "pancard"){
                    $titles[] = "Token Quantity";
                }
                
                if($type == "directpancard"){
                    $titles[] = "Type";
                }
                
                if($type == "nsdlaccount"){
                    $titles[] = "Pan";
                }
        
        		if($type == "directpancard" || $type == "nsdlaccount"){
                    $titles[] = "SSOID";
                	$titles[] = "KIOSKCODE";
                	$titles[] = "KIOSKNAME";
                	$titles[] = "TEHSIL";
                	$titles[] = "MOBILE";
                	$titles[] = "EMAIL";
                	$titles[] = "DISTRICT";
                }

                if($type == "offlinebillpay" || $type == "billpay"){
                    $titles[] = "Consumer Name";
                    $titles[] = "Due Date";
                }

                if (\Myhelper::hasRole(['admin', 'distributor', 'md', 'whitelable'], $userid)){
                    $titles[] = "Distributor Name";
                    $titles[] = "Distributor Mobile";
                    $titles[] = "Distributor Profit";
                }
                
                if (\Myhelper::hasRole(['admin', 'md', 'whitelable'], $userid)){
                    $titles[] = "MD Name";
                    $titles[] = "MD Mobile";
                    $titles[] = "MD Profit";
                }
                
                if (\Myhelper::hasRole(['admin', 'whitelable'], $userid)){
                    $titles[] = "Whitelable Name";
                    $titles[] = "Whitelable Mobile";
                    $titles[] = "Whitelable Profit";
                }
        
                $exportData = $query->select($selects)->get()->toArray();
                break;

            case 'ledger':
            case 'mainwallet':
                $tables = "reports";
                $query = \DB::table($tables)->leftJoin('users', 'users.id', '=', $tables.'.credit_by')
                        ->leftJoin('providers', 'providers.id', '=', $tables.'.provider_id')
                        ->orderBy($tables.'.id', 'desc');

                if(!empty($request->agent) && \Myhelper::hasRole("admin", $userid)){
                    $query->where($tables.'.user_id', $request->agent);
                }

                if(!empty($request->agent) && \Myhelper::hasRole("admin", $userid)){
                    $query->where($tables.'.user_id', $request->agent);
                }else{
                    $query->where($tables.'.user_id', $userid);
                }

                $dateFilter = 1;
                if(!empty($request->searchtext)){
                    $serachDatas = ['number', 'txnid', 'payid', 'refno', 'id'];
                    $query->where( function($q) use($request, $serachDatas, $tables){
                        foreach ($serachDatas as $value) {
                            $q->orWhere($tables.".".$value , 'like', '%'.$request->searchtext.'%');
                        }
                    });
                    $dateFilter = 0;
                }

                if((isset($request->fromdate) && !empty($request->fromdate)) && (isset($request->todate) && !empty($request->todate))){
                    if($request->fromdate == $request->todate){
                        $query->whereDate($tables.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                    }else{
                        $query->whereBetween($tables.'.created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);
                    }
                }elseif($dateFilter && isset($request->fromdate) && !empty($request->fromdate)){
                    $query->whereDate($tables.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                }

                $selects = ['id','mobile' ,'number', 'txnid', 'api_id', 'amount', 'profit', 'charge','tds', 'gst', 'payid', 'refno', 'balance', 'status', 'rtype', 'trans_type', 'user_id', 'credit_by', 'created_at', 'product', 'remark','option1', 'option3', 'option2', 'option5'];

                foreach ($selects as $select) {
                    $selectData[] = $table.".".$select;
                }

                $selectData[] = 'users.name as username';
                $selectData[] = 'users.mobile as usermobile';
                $selectData[] = 'sender.name as sendername';
                $selectData[] = 'apis.type as apitype';

                $datas = $query->select($selectData)->get();

                $titles = ['Date', 'User Details', "Payee Details", 'Transaction Details', 'Transaction Type', 'Status', "Product", "Amount", "Commission", "Charge", "Tds", 'Opening Balance', 'Tds', 'Credit', 'Debit'];

                $exportData = [];
                
                foreach ($datas as $record) {
                    $data['created_at'] = $record->created_at;
                    $data['userdetails'] = $record->username." (".$record->user_id.")";
                    $data['senderdetails'] = $record->sendername." (".$record->credit_by.")";
                    $data['txn'] = $record->number."/".$record->mobile."/".$record->refno;
                    $data['number'] = $record->trans_type;
                    $data['status'] = $record->status;
                    $data['product'] = $record->product;
                    $data['amount'] = " ".round($record->amount, 2);
                    $data['profit'] = " ".round($record->profit, 2);
                    $data['charge'] = " ".round($record->charge, 2);
                    $data['tds'] = " ".round($record->tds, 2);
                    $data['balance'] = " ".round($record->balance, 2);

                    if($record->apitype == "aeps" || $record->apitype == "matm"){
                        if($record->option1 == "M"){
                            if($record->trans_type == "credit"){
                                $data['credit'] = $record->amount - $record->charge;
                                $data['debit']  = '';
                            }elseif($record->trans_type == "debit"){
                                $data['credit'] = '';
                                $data['debit']  = $record->amount - $record->charge;
                            }else{
                                $data['credit'] = '';
                                $data['debit']  = '';
                            }
                        }else{
                            if($record->trans_type == "credit"){
                                $data['credit'] = $record->amount + $record->profit - $record->tds;
                                $data['debit']  = '';
                            }elseif($record->trans_type == "debit"){
                                $data['credit'] = '';
                                $data['debit']  = $record->amount + $record->profit - $record->tds;
                            }else{
                                $data['credit'] = '';
                                $data['debit']  = '';
                            }
                        }
                    }else if($record->apitype == "payout"){
                        if($record->trans_type == "credit"){
                            $data['credit'] = $record->amount + $record->charge;
                            $data['debit']  = '';
                        }elseif($record->trans_type == "debit"){
                            $data['credit'] = '';
                            $data['debit']  = $record->amount + $record->charge;
                        }else{
                            $data['credit'] = '';
                            $data['debit']  = '';
                        }
                    }else{
                        if($record->rtype == "main"){
                            if($record->trans_type == "credit"){
                                $data['credit'] = $record->amount + $record->charge - ($record->profit - $request->tds - $request->gst);
                                $data['debit']  = '';
                            }elseif($record->trans_type == "debit"){
                                $data['credit'] = '';
                                $data['debit']  = $record->amount + $record->charge - ($record->profit - $request->tds - $request->gst);
                            }else{
                                $data['credit'] = '';
                                $data['debit']  = '';
                            }
                        }else{
                            if($record->trans_type == "credit"){
                                $data['credit'] = $record->amount + $record->profit - $record->tds;
                                $data['debit']  = '';
                            }elseif($record->trans_type == "debit"){
                                $data['credit'] = '';
                                $data['debit']  = $record->amount + $record->profit - $record->tds;
                            }else{
                                $data['credit'] = '';
                                $data['debit']  = '';
                            }
                        }
                    }
                    array_push($exportData, $data);
                }
                break;
            
            case 'fundrequest':
                $table = "fundreports";
                $query = \DB::table($table)
                        ->leftJoin('users as user', 'user.id', '=', $table.'.user_id')
                        ->leftJoin('users as sender', 'sender.id', '=', $table.'.credited_by')
                        ->leftJoin('fundbanks as fundbank', 'fundbank.id', '=', $table.'.fundbank_id')
                        ->orderBy($table.'.id', 'desc');
                $dateFilter = 1;

                if(!empty($request->searchtext)){
                    $serachDatas = ['ref_no', 'amount', 'id'];
                    $query->where( function($q) use($request, $serachDatas, $table){
                        foreach ($serachDatas as $value) {
                            $q->orWhere($table.".".$value , $request->searchtext);
                        }
                    });
                    $dateFilter = 0;
                }
                if((isset($request->fromdate) && !empty($request->fromdate)) && (isset($request->todate) && !empty($request->todate))){
                    if($request->fromdate == $request->todate){
                        $query->whereDate($table.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                    }else{
                        $query->whereBetween($table.'.created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);
                    }
                }elseif($dateFilter && isset($request->fromdate) && !empty($request->fromdate)){
                    $query->whereDate($table.'.created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
                }

                $selects = ['created_at', 'type', 'ref_no', 'paydate', 'remark', 'status', 'paymode', 'amount'];

                foreach ($selects as $select) {
                    $selectData[] = $table.".".$select;
                }

                $selectData[] = 'fundbank.account as bankaccount';
                $selectData[] = 'fundbank.branch as bankbranch';
                $selectData[] = 'fundbank.name as bankname';
                $selectData[] = 'user.name as username';
                $selectData[] = 'user.mobile as usermobile';
                $selectData[] = 'sender.name as sendername';

                $exportData = $query->select($selectData)->get();
                $titles = ['Date', 'Type', "Ref No", 'Pay Date', 'Remark', 'Status', "Paymode", "Amount", "Deposite Account", 'Deposite Branch', "Deposite Bank", "Agent Name", "Agent Mobile", "Approved By"];
                break;

            case 'asm':
            case 'whitelable':
            case 'md':
            case 'distributor':
            case 'retailer':
            case 'statehead':
            case 'web':
            case 'kycpending':
            case 'kycsubmitted':
            case 'kycrejected':
            case 'other':
                $table = "users";
                $query = \DB::table('users');
                $query->leftJoin('companies', 'companies.id', '=', 'users.company_id');
                $query->leftJoin('roles', 'roles.id', '=', 'users.role_id');
                $query->leftJoin('users as parents', 'parents.id', '=', 'users.parent_id');

                switch ($request->type) {
                    case 'statehead':
                    case 'asm':
                    case 'whitelable':
                    case 'md':
                    case 'distributor':
                    case 'retailer':
                        $query->where('roles.slug', $type);
                    break;

                    case 'web':
                        $query->where($table.'.type', "web");
                    break;

                    case 'other':
                        $query->whereNotIn('roles.slug', ['statehead', 'asm', 'whitelable', 'md', 'distributor', 'retailer', 'admin']);
                    break;

                    case 'kycpending':
                        $query->whereIn($table.'.kyc', ['pending']);
                    break;

                    case 'kycsubmitted':
                        $query->whereIn($table.'.kyc', ['submitted']);
                    break;
                        
                    case 'kycrejected':
                        $$query->whereIn($table.'.kyc', ['rejected']);
                    break;
                }

                $titles = [
                    'Id', 
                    'Date' ,
                    'Name', 
                    'Email', 
                    'Mobile', 
                    'Role', 
                    'Main Balance', 
                    'Aeps Balance', 
                    'Locaked Balance', 
                    'Parent Name', 
                    'Parent Mobile', 
                    'Company', 
                    'Status' ,
                    'address', 
                    'City', 
                    'State',
                    'Pincode',
                    'Shopname',
                    'Pancard',
                    'Aadhar Card'
                ];

                $selects = [
                    $table.'.id', 
                    $table.'.created_at', 
                    $table.'.name', 
                    $table.'.email', 
                    $table.'.mobile', 
                    'roles.name as rolename', 
                    $table.'.mainwallet', 
                    $table.'.aepswallet', 
                    $table.'.lockedamount', 
                    'parents.name   as parentname', 
                    'parents.mobile as parentmobile', 
                    'companies.companyname' , 
                    $table.'.status' , 
                    $table.'.address',
                    $table.'.city', 
                    $table.'.state', 
                    $table.'.pincode'   , 
                    $table.'.shopname', 
                    $table.'.pancard', 
                    $table.'.aadharcard'
                ];
                $exportData = $query->select($selects)->get()->toArray();
            break;

            case 'fingagent':
                $table = "fingagents";
                $query = \DB::table('fingagents');
                $query->leftJoin('users', 'users.id', '=', 'fingagents.user_id');

                $titles = [
                    'MerchantLoginId',
                    'MerchantLoginPin',
                    'MerchantName',
                    'MerchantAddress',
                    'MerchantCityName',
                    'MerchantState',
                    'MerchantPhoneNumber',
                    'UserPan',
                    'MerchantPinCode',
                    'MerchantAadhar', 
                    'Status',
                    'Via',
                    'Remark', 
                    'Lat',
                    'Lon',
                    'Date',
                    'Agent ID',
                    'Agent Name',
                    'Agent Mobile'
                ];

                $selects = [
                    $table.'.merchantLoginId',
                    $table.'.merchantLoginPin',
                    $table.'.merchantName',
                    $table.'.merchantAddress',
                    $table.'.merchantCityName',
                    $table.'.merchantState',
                    $table.'.merchantPhoneNumber',
                    $table.'.userPan',
                    $table.'.merchantPinCode',
                    $table.'.merchantAadhar',
                    $table.'.status',
                    $table.'.via',
                    $table.'.remark',
                    $table.'.lat',
                    $table.'.lon',
                    $table.'.created_at',
                    'users.id',
                    'users.name',
                    "users.mobile"
                ];
                $exportData = $query->select($selects)->get()->toArray();

            case 'paysagent':
                $table = "aepsusers";
                $query = \DB::table('aepsusers');
                $query->leftJoin('users', 'users.id', '=', 'aepsusers.user_id');

                $titles = [
                    'MerchantLoginId',
                    'MerchantLoginPin',
                    'MerchantName',
                    'MerchantEmail',
                    'MerchantShopname',
                    'MerchantAddress',
                    'MerchantCityName',
                    'MerchantState',
                    'MerchantPhoneNumber',
                    'UserPan',
                    'MerchantPinCode',
                    'MerchantAadhar', 
                    'Status',
                    'Via',
                    'Lat',
                    'Lon',
                    'Date',
                    'Agent ID',
                    'Agent Name',
                    'Agent Mobile'
                ];

                $selects = [
                    $table.'.merchantLoginId',
                    $table.'.merchantLoginPin',
                    $table.'.merchantName',
                    $table.'.merchantEmail',
                    $table.'.merchantShopname',
                    $table.'.merchantAddress',
                    $table.'.merchantCityName',
                    $table.'.merchantState',
                    $table.'.merchantPhoneNumber',
                    $table.'.userPan',
                    $table.'.merchantPinCode',
                    $table.'.merchantAadhar',
                    $table.'.status',
                    $table.'.via',
                    $table.'.lat',
                    $table.'.lon',
                    $table.'.created_at',
                    'users.id',
                    'users.name',
                    "users.mobile"
                ];
                $exportData = $query->select($selects)->get()->toArray();
            break;

            case 'payoutaccount':
                $table = "contacts";
                $query = \DB::table('contacts');
                $query->leftJoin('users', 'users.id', '=', 'contacts.user_id');

                if(!empty($request->agent) && \Myhelper::hasRole("admin", $userid)){
                    $query->where($tables.'.user_id', $request->agent);
                }

                $titles = [
                    'Name',
                    'Account',
                    'Bank',
                    'Ifsc',
                    'Agent ID',
                    'Agent Name',
                    'Agent Mobile'
                ];

                $selects = [
                    $table.'.name',
                    $table.'.account',
                    $table.'.bank',
                    $table.'.ifsc',
                    'users.id',
                    'users.name',
                    "users.mobile"
                ];
                $exportData = $query->select($selects)->get()->toArray();
            break;
        }

        $excelData[] = $titles;
        $excelData[] = json_decode(json_encode($exportData), true);
        
        $export = new ReportExport($excelData);
        return \Excel::download($export, $type.'.csv');
    }
}
