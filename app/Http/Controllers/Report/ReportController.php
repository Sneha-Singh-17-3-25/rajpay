<?php
namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index($type="aeps", $id=0)
    {
        $data['type'] = $type;
        $data['id'] = $id;

        switch ($type) {
            case 'ledger':
            case 'aepsledger':
                return view('statement.ledger')->with($data);
                break;

            case 'nps':
                return view('statement.nps')->with($data);
                break;
            
            default:
                return view('statement.transaction')->with($data);
                break;
        }
    }

    public function fetchData(Request $request)
    {
        if($request->has('user_id')){
            $userid = $request->user_id;
        }else{
            if($request->type == ""){
                $userid = 1;
            }else{
                $userid = \Auth::id();
            }
        }

        $data = [];
        $dateFilter = 1;
        switch ($request->type) {
            case 'pancard':
            case 'billpay':
            case 'offlinebillpay':
            case 'recharge':
            case 'dmt':
            case 'directpancard':
            case 'nsdlaccount':
                $tables = "reports";
                $query  = \DB::table($tables)
                        ->leftJoin('users', 'users.id', '=', $tables.'.user_id')
                        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
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

                    case 'directpancard':
                        $dateFilter = 0;
                        $query->where($tables.'.product', 'directpancard');
                        break;
                        
                    case 'nsdlaccount':
                        $dateFilter = 0;
                        $query->where($tables.'.product', 'nsdl');
                        break;
                    
                    case 'dmt':
                        $query->where($tables.'.product', 'dmt');
                        break;
                }
        
                if(!empty($request->searchtext)){
                    $serachDatas = ['txnid', 'refno', 'mobile', 'payid'];
                    $query->where( function($q) use($request, $serachDatas, $tables){
                        foreach ($serachDatas as $value) {
                            $q->orWhere($tables.".".$value , 'like', '%'.$request->searchtext.'%');
                        }
                    });
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

                $selects = ['id','mobile' ,'number', 'txnid', 'api_id', 'amount', 'profit', 'charge','tds', 'gst', 'payid', 'refno', 'balance', 'status', 'rtype', 'trans_type', 'user_id', 'credit_by', 'created_at', 'product', 'remark','option1', 'option3', 'option2', 'option5', 'option4', 'option6','option7', 'option8','option10','description', 'wprofit', 'mdprofit', 'disprofit','remark','via'];

                $selectData = [];
                foreach ($selects as $select) {
                    $selectData[] = $tables.".".$select;
                }

                $selectData[] = 'users.name as username';
                $selectData[] = 'users.mobile as usermobile';
                $selectData[] = 'users.agentcode as useragentcode';
                $selectData[] = 'users.shopname as usershop';
                $selectData[] = 'roles.slug as userrole';
                $selectData[] = 'apis.name as apiname';
                $selectData[] = 'providers.name as providername';

                $exportData = $query->select($selectData);  
                $count = intval($exportData->count());

                if(isset($request['length']) && $request->length != "-1"){
                    $exportData->skip($request['start'])->take($request['length']);
                }

                $data = array(
                    "draw"            => intval($request['draw']),
                    "recordsTotal"    => $count,
                    "recordsFiltered" => $count,
                    "data"            => $exportData->get()
                );
                break;

            case 'aeps':
            case 'aadharpay':
            case 'ministatement':
            case 'payout':
            case 'matm':
            case '2fa':
                $tables = "aepsreports";
                $query  = \DB::table($tables)
                        ->leftJoin('users', 'users.id', '=', $tables.'.user_id')
                        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                        ->leftJoin('apis', 'apis.id', '=', $tables.'.api_id')
                        ->leftJoin('providers', 'providers.id', '=', $tables.'.provider_id')
                        ->orderBy($tables.'.id', 'desc')
                        ->where($tables.'.rtype', "main")
                        ->where($tables.'.status', "!=","refunded");

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

                    case '2fa':
                        $query->where($tables.".product", "2fa");
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
                    $serachDatas = ['number', 'txnid', 'amount', 'refno', 'id'];
                    $query->where( function($q) use($request, $serachDatas, $tables){
                        foreach ($serachDatas as $value) {
                            $q->orWhere($tables.".".$value , 'like', '%'.$request->searchtext.'%');
                        }
                    });
                    $dateFilter = 0;
                }

                if(isset($request->product) && !empty($request->product)){
                    switch ($request->type) {
                        case 'aeps':
                        case 'aadharpay':
                        case 'ministatement':
                        case 'matm':
                            $query->where($tables.'.api_id', $request->product);
                            break;

                        case 'payout':
                            $query->where($tables.'.option1', $request->product);
                            break;
                    }
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

                $selects = ['id','mobile' ,'number', 'txnid', 'api_id', 'amount', 'profit', 'charge','tds', 'gst', 'payid', 'refno', 'balance', 'status', 'rtype', 'trans_type', 'user_id', 'credit_by', 'created_at', 'product', 'remark','option1', 'option3', 'option2', 'option5', 'option4', 'option7', 'option8', 'description', 'wprofit', 'mdprofit', 'disprofit','remark','via'];

                $selectData = [];
                foreach ($selects as $select) {
                    $selectData[] = $tables.".".$select;
                }

                $selectData[] = 'users.name as username';
                $selectData[] = 'users.mobile as usermobile';
                $selectData[] = 'users.agentcode as useragentcode';
                $selectData[] = 'users.shopname as usershop';
                $selectData[] = 'roles.slug as userrole';
                $selectData[] = 'apis.name as apiname';
                $selectData[] = 'providers.name as providername';

                $exportData = $query->select($selectData);  
                $count = intval($exportData->count());

                if(isset($request['length']) && $request->length != "-1"){
                    $exportData->skip($request['start'])->take($request['length']);
                }

                $data = array(
                    "draw"            => intval($request['draw']),
                    "recordsTotal"    => $count,
                    "recordsFiltered" => $count,
                    "data"            => $exportData->get()
                );
                break;

            case 'ledger':
            case 'mainwallet':
                $tables = "reports";
                $query = \DB::table($tables)->leftJoin('users', 'users.id', '=', $tables.'.credit_by')
                        ->leftJoin('providers', 'providers.id', '=', $tables.'.provider_id')
                        ->where($tables.'.status', "!=", "failed")
                        ->orderBy($tables.'.id', 'desc');

                if(!empty($request->agent) && \Myhelper::hasRole("admin", $userid)){
                    $query->where($tables.'.user_id', $request->agent);
                }else{
                    $query->where($tables.'.user_id', $userid);
                }

                $dateFilter = 1;
                if(!empty($request->searchtext)){
                    $serachDatas = ['number', 'txnid', 'amount', 'refno', 'id'];
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

                $selectData = [];
                foreach ($selects as $select) {
                    $selectData[] = $tables.".".$select;
                }

                $selectData[] = 'users.name as username';
                $selectData[] = 'users.mobile as usermobile';
                $selectData[] = 'providers.name as providername';

                
                $exportData = $query->select($selectData);   
                $count = intval($exportData->count());

                if(isset($request['length']) && $request->length != "-1"){
                    $exportData->skip($request['start'])->take($request['length']);
                }

                $data = array(
                    "draw"            => intval($request['draw']),
                    "recordsTotal"    => $count,
                    "recordsFiltered" => $count,
                    "data"            => $exportData->get()
                );
                break;
            
            case 'aepsledger':
                $tables = "aepsreports";
                $query = \DB::table($tables)->leftJoin('users', 'users.id', '=', $tables.'.credit_by')
                        ->leftJoin('providers', 'providers.id', '=', $tables.'.provider_id')
                        ->whereIn($tables.'.status', ["success", "complete", "reversed", "refunded", 'pending'])
                        ->orderBy($tables.'.id', 'desc');

                if(!empty($request->agent) && \Myhelper::hasRole("admin", $userid)){
                    $query->where($tables.'.user_id', $request->agent);
                }else{
                    $query->where($tables.'.user_id', $userid);
                }

                $dateFilter = 1;
                if(!empty($request->searchtext)){
                    $serachDatas = ['number', 'txnid', 'amount', 'refno', 'id'];
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

                $selectData = [];
                foreach ($selects as $select) {
                    $selectData[] = $tables.".".$select;
                }

                $selectData[] = 'users.name as username';
                $selectData[] = 'users.mobile as usermobile';
                $selectData[] = 'providers.name as providername';

                
                $exportData = $query->select($selectData);   
                $count = intval($exportData->count());

                if(isset($request['length']) && $request->length != "-1"){
                    $exportData->skip($request['start'])->take($request['length']);
                }

                $data = array(
                    "draw"            => intval($request['draw']),
                    "recordsTotal"    => $count,
                    "recordsFiltered" => $count,
                    "data"            => $exportData->get()
                );
                break;
            
            case 'fundrequest':
                $table = "fundreports";
                $query = \DB::table($table)
                        ->leftJoin('users as user', 'user.id', '=', $table.'.user_id')
                        ->leftJoin('users as sender', 'sender.id', '=', $table.'.credited_by')
                        ->leftJoin('fundbanks as fundbank', 'fundbank.id', '=', $table.'.fundbank_id')
                        ->orderBy($table.'.id', 'desc');
                $query->where($table.'.user_id', $request->user_id);
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

                if(isset($request->product) && !empty($request->product) && $request->product != '' && $request->product != null){
                    $query->where($table.'.type', $request->product);
                    $dateFilter = 0;
                }

                if(isset($request->status) && $request->status != '' && $request->status != null){
                    $query->where($table.'.status', $request->status);
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

                $selects = ['type', 'fundbank_id', 'ref_no', 'paydate', 'remark', 'status', 'user_id', 'credited_by', 'paymode', 'amount', 'id', 'created_at', 'updated_at'];

                foreach ($selects as $select) {
                    $selectData[] = $table.".".$select;
                }

                $selectData[] = 'user.name as username';
                $selectData[] = 'user.mobile as usermobile';
                $selectData[] = 'sender.name as sendername';
                $selectData[] = 'sender.mobile as sendermobile';
                $selectData[] = 'fundbank.name as bankname';
                $selectData[] = 'fundbank.branch as bankbranch';
                $selectData[] = 'fundbank.account as bankaccount';

                $exportData = $query->select($selectData);

                if(isset($request['length']) && $request->length != "-1"){
                    $exportData->skip($request['start'])->take($request['length']);
                }

                $data = array(
                    "draw"            => intval($request['draw']),
                    "recordsTotal"    => intval($exportData->count()),
                    "recordsFiltered" => intval($exportData->count()),
                    "data"            => $exportData->get()
                );
                break;
        }
        echo json_encode($data);
    }
}
