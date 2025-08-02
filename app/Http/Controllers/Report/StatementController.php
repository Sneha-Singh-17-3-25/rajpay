<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Utireport;
use Carbon\Carbon;
use App\Model\Rechargereport;
use App\Model\Billpayreport;
use App\Model\Moneyreport;
use App\User;
use App\Model\AccountStatement;
use App\Model\Fundreport;
use App\Model\Nsdlpan;

class StatementController extends Controller
{
    public function index($type, $id=0, $status="pending")
    {
        if($id != 0){
            $agentfilter = "hide";
        }else{
            $agentfilter = "";
        }

        $data = ['id' => $id, 'agentfilter' => $agentfilter, 'type'=>$type];
        if($id != 0){
            $user = User::where('id', $id)->first();
            if (!$user) {
                abort('404');
            }
        }
        $file = $type;

        switch ($type) {
            case 'account':
                if($id == 0){
                    $permission = "account_statement";
                }else{
                    $permission = "member_account_statement_view";
                }
                break;
            
            case 'utiid':
            case 'nsdlid':
                if($id == 0){
                    $permission = "utiid_statement";
                }else{
                    $permission = "member_utiid_statement_view";
                }
                $file = "utiid";
                break;

            case 'utipancard':
            case 'billpayment':
            case 'recharge':
            case 'money':
            case 'aeps':
            case 'matm':
            case 'payout':
                $file = "transaction";
                if($id == 0){
                    $permission = $type."_statement";
                }else{
                    $permission = "member_".$type."_statement_view";
                }
                break;

            case 'faepsid':
            case 'paepsid':
                if($id == 0){
                    $permission = "aepsid_statement";
                }else{
                    $permission = "member_aepsid_statement";
                }
                break;

            default:
                abort(404);
                break;
        }

        if (!\Myhelper::can($permission)) {
            abort(403);
        }

        return view('statement.'.$file)->with($data);
    }

    public function export(Request $post, $type)
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '-1');
        if(\Myhelper::hasRole(['md', 'distributor', 'retailer', 'apiuser'])){
            $parentData = \Myhelper::getParents(\Auth::id());
        }else{
            $user = User::whereHas('role', function ($q){
                $q->where('slug', 'admin');
            })->first();
            
            $parentData = \Myhelper::getParents($user->id);
        }

        switch ($type) {
            case 'whitelable':
            case 'md':
            case 'distributor':
            case 'retailer':
            case 'retaillite':
                $table = "users.";
                $query = \DB::table('users');
                $query->leftJoin('companies', 'companies.id', '=', 'users.company_id');
                $query->leftJoin('roles', 'roles.id', '=', 'users.role_id');
                $query->leftJoin('users as parents', 'parents.id', '=', 'users.parent_id');
                $query->where('roles.slug', '=', $type)->whereIn($table.'id', $parentData);
            break;

            case 'fundrequest':
                $table = "fundreports.";
                $query = \DB::table('fundreports');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'fundreports.user_id');
                $query->leftJoin('users as sender', 'sender.id', '=', 'fundreports.credited_by');
                $query->leftJoin('fundbanks', 'fundbanks.id', '=', 'fundreports.fundbank_id');

                $query->where($table.'credited_by', \Auth::id());
                break;

            case 'fund':
                $table = "reports.";
                $query = \DB::table('reports')->where($table.'api_id', '2');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');
                $query->leftJoin('users as sender', 'sender.id', '=', 'reports.credit_by');

                $query->where($table.'user_id', \Auth::id());
                break;

            case 'aepsfundrequestview':
                if(\Myhelper::hasNotRole('admin')){
                    return redirect()->back();
                }
                $table = "aepsfundrequests.";
                $query = \DB::table('aepsfundrequests')->where($table.'status', 'pending')->where($table.'pay_type', 'manual');

                $query->leftJoin('users', 'users.id', '=', 'aepsfundrequests.user_id');
                break;
                
            case 'aepsfundrequestviewpayout':
                if(\Myhelper::hasNotRole('admin')){
                    return redirect()->back();
                }
                $table = "aepsfundrequests.";
                $query = \DB::table('aepsfundrequests')->where($table.'pay_type', 'payout');

                $query->leftJoin('users', 'users.id', '=', 'aepsfundrequests.user_id');
                break;

            case 'aepsfundrequest':
            case 'aepsfundrequestviewall':
                $table = "aepsfundrequests.";
                $query = \DB::table('aepsfundrequests');

                $query->leftJoin('users', 'users.id', '=', 'aepsfundrequests.user_id');
                if($type == "aepsfundrequest"){
                    $query->where($table.'user_id', \Auth::id());
                }else{
                    if(\Myhelper::hasNotRole('admin')){
                        return redirect()->back();
                    }
                }
                break;
                
            case 'matmfundrequestview':
                if(\Myhelper::hasNotRole('admin')){
                    return redirect()->back();
                }
                $table = "microatmfundrequests.";
                $query = \DB::table('microatmfundrequests')->where($table.'status', 'pending')->where($table.'pay_type', 'manual');

                $query->leftJoin('users', 'users.id', '=', 'microatmfundrequests.user_id');
                break;
                
            case 'matmfundrequestviewpayout':
                if(\Myhelper::hasNotRole('admin')){
                    return redirect()->back();
                }
                $table = "microatmfundrequests.";
                $query = \DB::table('microatmfundrequests')->where($table.'pay_type', 'payout');

                $query->leftJoin('users', 'users.id', '=', 'microatmfundrequests.user_id');
                break;

            case 'matmfundrequest':
            case 'matmfundrequestviewall':
                $table = "microatmfundrequests.";
                $query = \DB::table('microatmfundrequests');

                $query->leftJoin('users', 'users.id', '=', 'microatmfundrequests.user_id');
                if($type == "matmfundrequest"){
                    $query->where($table.'user_id', \Auth::id());
                }else{
                    if(\Myhelper::hasNotRole('admin')){
                        return redirect()->back();
                    }
                }
                break;

            case 'aepsagentstatement':
                $query = \DB::table('mahaagents');
                $table = "mahaagents.";

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->whereIn($table.'user_id', $parentData);
                }
            break;

            case 'utiid':
                $query = \DB::table('utiids');
                $table = "utiids.";
                $query->leftJoin('users', 'users.id', '=', 'utiids.user_id');
                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->whereIn($table.'user_id', $parentData);
                }
            break;

            case 'wallet':
                $table = "reports.";
                $query = \DB::table('reports');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'reports.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->where($table.'user_id', \Auth::id());
                }
                break;

            case 'awallet':
                $table = "aepsreports.";
                $query = \DB::table('aepsreports');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'aepsreports.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->where($table.'user_id', \Auth::id());
                }
                break;
                
            case 'matmwallet':
                $table = "microatmreports.";
                $query = \DB::table('microatmreports');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'microatmreports.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->where($table.'user_id', \Auth::id());
                }
                break;
                
            case 'device':   
                $table = "orders.";
                $query = \DB::table('orders');
                $query->leftJoin('users as retailer', 'retailer.id', '=', 'orders.user_id');
                $query->where($table.'type', 'device');
                if(\Myhelper::hasNotRole('admin')){
                    $query->where($table.'user_id', \Auth::id());
                }
                break;
                
            case 'retailerid':    
                $table = "orders.";
                $query = \DB::table('orders');
                $query->leftJoin('users as retailer', 'retailer.id', '=', 'orders.user_id');
                $query->where($table.'type', 'retailerid');
                if(\Myhelper::hasNotRole('admin')){
                    $query->where($table.'user_id', \Auth::id());
                }
                break;
                
            case 'money2020':
                $table = "report2020s.";
                $query = \DB::table('report2020s')->where($table.'rtype', 'main')->where($table.'product', "dmt");

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'report2020s.user_id');
                $query->leftJoin('users as distributor', 'distributor.id', '=', 'report2020s.disid');
                $query->leftJoin('users as md', 'md.id', '=', 'report2020s.mdid');
                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'report2020s.wid');
                $query->leftJoin('apis', 'apis.id', '=', 'report2020s.api_id');
                $query->leftJoin('providers', 'providers.id', '=', 'report2020s.provider_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->whereIn($table.'user_id', $parentData);
                }
                break;
            
            case 'billpay2020':
                $table = "report2020s.";
                $query = \DB::table('report2020s')->where($table.'rtype', 'main')->where($table.'product', "billpay");

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'report2020s.user_id');
                $query->leftJoin('users as distributor', 'distributor.id', '=', 'report2020s.disid');
                $query->leftJoin('users as md', 'md.id', '=', 'report2020s.mdid');
                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'report2020s.wid');
                $query->leftJoin('apis', 'apis.id', '=', 'report2020s.api_id');
                $query->leftJoin('providers', 'providers.id', '=', 'report2020s.provider_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->whereIn($table.'user_id', $parentData);
                }
                break;
                
            case 'recharge2020':
                $table = "report2020s.";
                $query = \DB::table('report2020s')->where($table.'rtype', 'main')->where($table.'product', "recharge");

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'report2020s.user_id');
                $query->leftJoin('users as distributor', 'distributor.id', '=', 'report2020s.disid');
                $query->leftJoin('users as md', 'md.id', '=', 'report2020s.mdid');
                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'report2020s.wid');
                $query->leftJoin('apis', 'apis.id', '=', 'report2020s.api_id');
                $query->leftJoin('providers', 'providers.id', '=', 'report2020s.provider_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->whereIn($table.'user_id', $parentData);
                }
                break;
                
            case 'utipancard2020':
                $table = "report2020s.";
                $query = \DB::table('report2020s')->where($table.'rtype', 'main')->where($table.'product', "utipancard");

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'report2020s.user_id');
                $query->leftJoin('users as distributor', 'distributor.id', '=', 'report2020s.disid');
                $query->leftJoin('users as md', 'md.id', '=', 'report2020s.mdid');
                $query->leftJoin('users as whitelable', 'whitelable.id', '=', 'report2020s.wid');
                $query->leftJoin('apis', 'apis.id', '=', 'report2020s.api_id');
                $query->leftJoin('providers', 'providers.id', '=', 'report2020s.provider_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->whereIn($table.'user_id', $parentData);
                }
                break;
                
            case 'wallet2020':
                $table = "report2020s.";
                $query = \DB::table('report2020s');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'report2020s.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->where($table.'user_id', \Auth::id());
                }
                break;

            case 'awallet2020':
                $table = "aepsreport2020s.";
                $query = \DB::table('aepsreport2020s');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'aepsreport2020s.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->where($table.'user_id', \Auth::id());
                }
                break;
                
            case 'matmwallet2020':
                $table = "microatmreport2020s.";
                $query = \DB::table('microatmreport2020s');

                $query->leftJoin('users as retailer', 'retailer.id', '=', 'microatmreport2020s.user_id');

                if(isset($post->agent) && $post->agent != '' && $post->agent != 0){
                    $query->where($table.'user_id', $post->agent);
                }else{
                    $query->where($table.'user_id', \Auth::id());
                }
                break;
            
            default:
                # code...
                break;
        }

        if((isset($post->fromdate) && !empty($post->fromdate)) 
            && (isset($post->todate) && !empty($post->todate))){
            if($post->fromdate == $post->todate){
                $query->whereDate($table.'created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
            }else{
                $query->whereBetween($table.'created_at', [Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $post->todate)->addDay(1)->format('Y-m-d')]);
            }
        }elseif (isset($post->fromdate) && !empty($post->fromdate)) {
            if(!in_array($type, ['whitelable', 'md', 'distributor', 'retailer', 'retaillite'])){
                $query->whereDate($table.'created_at','=', Carbon::createFromFormat('Y-m-d', $post->fromdate)->format('Y-m-d'));
            }
        }else{
            if(!in_array($type, ['whitelable', 'md', 'distributor', 'retailer'])){
                $query->whereDate($table.'created_at','=', date('Y-m-d'));
            }
        }

        if(isset($post->status) && $post->status != '' && $post->status != 'undefined'){
            switch ($post->type) {
                default:
                    $query->where($table.'status', $post->status);
                break;
            }
        }

        switch ($type) {
            case 'utipancard2020':
            case 'billpay2020':
            case 'recharge2020':
            case 'money2020':
                $datas = $query->get([$table.'*', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'report2020s.user_id']);
                break;
                
            case 'wallet2020':
                $datas = $query->get([$table.'*', 'retailer.name as username', 'retailer.mobile as usermobile', 'report2020s.user_id']);
                break;
                
            case 'recharge':
            case 'billpay':
                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge', 'reports.profit', 'reports.refno', 'reports.txnid', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'reports.user_id']);
                break;

            case 'pancard':
                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge', 'reports.profit', 'reports.refno', 'reports.txnid', 'reports.option1', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'reports.user_id']);
                break;

            case 'money':
                $datas = $query->get(['reports.id', 'reports.option5', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge', 'reports.profit', 'reports.refno', 'reports.txnid', 'reports.option1', 'reports.option2', 'reports.option3', 'reports.option4', 'reports.mobile', 'reports.option1', 'reports.status', 'reports.wid', 'reports.wprofit', 'reports.mdid', 'reports.mdprofit', 'reports.disid', 'reports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'reports.user_id']);
                break;

            case 'aeps':
            case 'aadharpay':
            case 'cashdeposit':
                // $name = $type.'report'.date('d_M_Y');
                // $titles = ['Transaction Id', 'Date','Api Name', 'Provider', 'Bc Id', 'Bc Mobile',' Amount', 'Profit', 'Ref No', 'Status', "Member Name", "Member Mobile"];

                // if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                //     $titles = array_merge($titles, ["Distributor Name", "Distributor Mobile", "Distributor Profit"]);
                // }

                // if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                //     $titles = array_merge($titles, ["MD Name", "MD Mobile", "Md Profit"]);
                // }

                // if(\Myhelper::hasRole(['whitelable', 'admin'])){
                //     $titles = array_merge($titles, ["Whitelable Name", "Whitelable Mobile", "Whitelable Profit"]);
                // }
                // array_merge($titles, ["W Id", "MD Id", "Dis Id", 'User Id']);
                // $datas = json_decode(json_encode($query->get(['aepsreports.id', 'aepsreports.created_at', 'apis.name as apiname', 'providers.name as providername', 'aepsreports.aadhar', 'aepsreports.mobile', 'aepsreports.amount', 'aepsreports.charge', 'aepsreports.refno', 'aepsreports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'aepsreports.disprofit', 'md.name as mdname', 'md.mobile as mdmobile', 'aepsreports.mdprofit', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'aepsreports.wprofit', 'aepsreports.wid', 'aepsreports.mdid', 'aepsreports.disid', 'aepsreports.user_id'])), true);
                
                // $export = new \App\Exports\ReportExport($datas);
                // return \Excel::download($export, $name.'.csv');
                $datas = $query->get(['aepsreports.id', 'aepsreports.created_at', 'apis.name as apiname', 'providers.name as providername', 'aepsreports.aadhar', 'aepsreports.mobile', 'aepsreports.amount', 'aepsreports.charge', 'aepsreports.refno', 'aepsreports.txnid', 'aepsreports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'aepsreports.disprofit', 'md.name as mdname', 'md.mobile as mdmobile', 'aepsreports.mdprofit', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'aepsreports.wprofit', 'aepsreports.wid', 'aepsreports.mdid', 'aepsreports.disid', 'aepsreports.user_id']);
                break;
                
            case 'matm':
                $datas = $query->get(['microatmreports.id', 'microatmreports.created_at', 'microatmreports.txnid', 'microatmreports.mobile', 'microatmreports.aadhar', 'microatmreports.amount', 'microatmreports.refno', 'microatmreports.charge', 'microatmreports.status', 'microatmreports.wid', 'microatmreports.wprofit', 'microatmreports.mdid', 'microatmreports.mdprofit', 'microatmreports.disid', 'microatmreports.disprofit', 'retailer.name as username', 'retailer.mobile as usermobile', 'whitelable.name as wname', 'whitelable.mobile as wmobile', 'md.name as mdname', 'md.mobile as mdmobile', 'distributor.name as disname', 'distributor.mobile as dismobile', 'apis.name as apiname', 'providers.name as providername', 'microatmreports.user_id']);
                break;

            case 'whitelable':
            case 'md':
            case 'distributor':
            case 'retailer':
            case 'retaillite':
                $datas = $query->get(['users.id','users.created_at','users.name','users.email','users.mobile','users.role_id','users.reference','users.company_id','users.mainwallet','users.aepswallet','users.status','users.address','users.state','users.city','users.pincode','users.shopname','users.gstin','users.pancard','users.aadharcard','users.bank','users.ifsc','users.account','users.mstock','users.dstock','users.rstock','companies.companyname as companyname','roles.name as rolename','parents.name as parentname','parents.mobile as parentmobile']);
            break;

            case 'fundrequest':
                $datas = $query->get(['fundreports.id', 'fundreports.created_at', 'fundreports.paymode', 'fundreports.amount', 'fundreports.amount', 'fundreports.ref_no', 'fundreports.paydate', 'fundreports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'sender.name as sendername', 'sender.mobile as sendermobile', 'fundbanks.name as fundbank']);
                break;

            case 'fund':
                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.amount', 'reports.refno', 'reports.product', 'reports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'sender.name as sendername', 'sender.mobile as sendermobile', 'reports.user_id', 'reports.credit_by', 'reports.remark']);
                break;

            case 'aepsfundrequestview':
            case 'aepsfundrequestviewpayout':
            case 'aepsfundrequest':
            case 'aepsfundrequestviewall':
            case 'aepsfundrequestviewall2020':
                $datas = $query->get(['aepsfundrequests.id', 'aepsfundrequests.created_at', 'aepsfundrequests.account', 'aepsfundrequests.payoutid', 'aepsfundrequests.payoutref', 'aepsfundrequests.bank', 'aepsfundrequests.ifsc', 'aepsfundrequests.amount', 'aepsfundrequests.type', 'aepsfundrequests.pay_type', 'aepsfundrequests.status', 'aepsfundrequests.remark', 'users.name as username', 'users.mobile as usermobile']);
                break;
                
            case 'matmfundrequestview':
            case 'matmfundrequestviewpayout':
            case 'matmfundrequest':
            case 'matmfundrequestviewall':
                $datas = $query->get(['microatmfundrequests.id', 'microatmfundrequests.created_at', 'microatmfundrequests.account', 'microatmfundrequests.payoutid', 'microatmfundrequests.payoutref', 'microatmfundrequests.bank', 'microatmfundrequests.ifsc', 'microatmfundrequests.amount', 'microatmfundrequests.type', 'microatmfundrequests.pay_type', 'microatmfundrequests.status', 'microatmfundrequests.remark', 'users.name as username', 'users.mobile as usermobile']);
                break;

            case 'aepsagentstatement':
                $datas = $query->get(['mahaagents.id', 'mahaagents.created_at', 'mahaagents.bc_id', 'mahaagents.bc_f_name', 'mahaagents.bc_l_name', 'mahaagents.bc_l_name', 'mahaagents.emailid', 'mahaagents.phone1', 'mahaagents.phone2']);
                break;

            case 'utiid':
                $datas = $query->get(['utiids.id', 'utiids.created_at', 'utiids.vleid', 'utiids.status', 'utiids.name', 'utiids.location', 'utiids.contact_person', 'utiids.pincode', 'utiids.state', 'utiids.state', 'utiids.email', 'utiids.mobile', 'utiids.remark', 'utiids.user_id', 'users.name as username', 'users.mobile as usermobile']);
                break;

            case 'wallet':
                $datas = $query->get(['reports.id', 'reports.created_at', 'reports.number', 'reports.amount', 'reports.charge', 'reports.profit', 'reports.status', 'retailer.name as username', 'retailer.mobile as usermobile', 'reports.user_id', 'reports.product', 'reports.rtype', 'reports.trans_type', 'reports.balance']);
                break;

            case 'awallet':
                $datas = $query->get(['aepsreports.id', 'aepsreports.created_at', 'aepsreports.payid', 'aepsreports.remark', 'aepsreports.aadhar', 'aepsreports.mobile', 'aepsreports.refno', 'retailer.name as username', 'retailer.mobile as usermobile', 'aepsreports.user_id', 'aepsreports.transtype', 'aepsreports.rtype', 'aepsreports.status', 'aepsreports.balance', 'aepsreports.amount', 'aepsreports.charge', 'aepsreports.type']);
                break;
                
            case 'matmwallet':
                $datas = $query->get(['microatmreports.id', 'microatmreports.created_at', 'microatmreports.payid', 'microatmreports.remark', 'microatmreports.aadhar', 'microatmreports.mobile', 'microatmreports.refno', 'retailer.name as username', 'retailer.mobile as usermobile', 'microatmreports.user_id', 'microatmreports.transtype', 'microatmreports.rtype', 'microatmreports.status', 'microatmreports.balance', 'microatmreports.amount', 'microatmreports.charge', 'microatmreports.type']);
                break;
                
            case 'retailerid':
            case 'device':
                $datas = $query->get(['orders.id', 'orders.created_at', 'orders.amount', 'orders.refno', 'orders.paydate', 'orders.remark', 'orders.status', 'retailer.name as username', 'retailer.mobile as usermobile']);
                break;
            
            
            default:
                # code...
                break;
        }
        //dd($datas);
        $excelData = array();
        switch ($type) {
            case 'recharge':
            case 'billpay':
            case 'billpay2020':
            case 'recharge2020':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Transaction Id', 'Date','Api Name', 'Provider', 'Number',' Amount', 'Charge', 'Profit', 'Ref No', 'Status', "Member Name", "Member Mobile"];

                if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Distributor Name", "Distributor Mobile", "Distributor Profit"]);
                }

                if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["MD Name", "MD Mobile", "Md Profit"]);
                }

                if(\Myhelper::hasRole(['whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Whitelable Name", "Whitelable Mobile", "Whitelable Profit"]);
                }

                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['apitype'] = $record->apiname;
                    $data['provider'] = $record->providername;
                    $data['number'] = $record->number;
                    $data['amount'] = $record->amount;
                    $data['charge'] = $record->charge;
                    $data['profit'] = $record->profit;
                    $data['refno'] = $record->refno;
                    $data['status'] = $record->status;
                    $data['username'] = $record->username;
                    $data['usermobile'] = $record->usermobile;

                    if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                        $data['disdetailsn'] = $record->disname;
                        $data['disdetailsm'] = $record->dismobile;
                        $data['disprofit'] = $record->disprofit;
                    }

                    if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                        $data['mddetailsn'] = $record->mdname;
                        $data['mddetailsm'] = $record->mdmobile;
                        $data['mdprofit'] = $record->mdprofit;
                    }

                    if(\Myhelper::hasRole(['whitelable', 'admin'])){
                        $data['wdetailsn'] = $record->wname;
                        $data['wdetailsm'] = $record->wmobile;
                        $data['wprofit'] = $record->wprofit;
                    }
                    array_push($excelData, $data);
                }
                break;

            case 'pancard':
            case 'utipancard2020':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Transaction Id', 'Date','Api Name', 'Vle Id', 'No of Token', ' Amount', 'Charge', 'Profit', 'Ref No', 'Status', "Member Name", "Member Mobile"];

                if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Distributor Name", "Distributor Mobile", "Distributor Profit"]);
                }

                if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["MD Name", "MD Mobile", "Md Profit"]);
                }

                if(\Myhelper::hasRole(['whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Whitelable Name", "Whitelable Mobile", "Whitelable Profit"]);
                }

                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['apitype'] = $record->apiname;
                    $data['number'] = $record->number;
                    $data['option1'] = $record->option1;
                    $data['amount'] = $record->amount;
                    $data['charge'] = $record->charge;
                    $data['profit'] = $record->profit;
                    $data['refno'] = $record->refno;
                    $data['status'] = $record->status;
                    $data['username'] = $record->username;
                    $data['usermobile'] = $record->usermobile;

                    if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                        $data['disdetailsn'] = $record->disname;
                        $data['disdetailsm'] = $record->dismobile;
                        $data['disprofit'] = $record->disprofit;
                    }

                    if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                        $data['mddetailsn'] = $record->mdname;
                        $data['mddetailsm'] = $record->mdmobile;
                        $data['mdprofit'] = $record->mdprofit;
                    }

                    if(\Myhelper::hasRole(['whitelable', 'admin'])){
                        $data['wdetailsn'] = $record->wname;
                        $data['wdetailsm'] = $record->wmobile;
                        $data['wprofit'] = $record->wprofit;
                    }
                    array_push($excelData, $data);
                }
                break;

            case 'money':
            case 'money2020':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Transaction Id', 'Date','Api Name', 'Provider', 'Remitter Name', 'Remitter Mobile', 'Beneficiary Name', 'Beneficiary Account', 'Beneficiary Bank', 'Beneficiary Ifsc',' Amount', 'Charge', 'Profit', 'Order Id', 'Ref No', 'Status', 'Aadhar/Pan', "Member Name", "Member Mobile"];

                if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Distributor Name", "Distributor Mobile", "Distributor Profit"]);
                }

                if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["MD Name", "MD Mobile", "Md Profit"]);
                }

                if(\Myhelper::hasRole(['whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Whitelable Name", "Whitelable Mobile", "Whitelable Profit"]);
                }

                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['apitype'] = $record->apiname;
                    $data['provider'] = $record->providername;
                    $data['rname'] = $record->option1;
                    $data['rmobile'] = $record->mobile;
                    $data['name'] = $record->option2;
                    $data['number'] = " ".$record->number." ";
                    $data['bank'] = $record->option3;
                    $data['ifsc'] = $record->option4;
                    $data['amount'] = $record->amount;
                    $data['charge'] = $record->charge;
                    $data['profit'] = $record->profit;
                    $data['txnid'] = $record->txnid;
                    $data['refno'] = $record->refno;
                    $data['status'] = $record->status;
                    $data['aadhar/pan'] = $record->option5;
                    $data['username'] = $record->username;
                    $data['usermobile'] = $record->usermobile;

                    if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                        $data['disdetailsn'] = $record->disname;
                        $data['disdetailsm'] = $record->dismobile;
                        $data['disprofit'] = $record->disprofit;
                    }

                    if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                        $data['mddetailsn'] = $record->mdname;
                        $data['mddetailsm'] = $record->mdmobile;
                        $data['mdprofit'] = $record->mdprofit;
                    }

                    if(\Myhelper::hasRole(['whitelable', 'admin'])){
                        $data['wdetailsn'] = $record->wname;
                        $data['wdetailsm'] = $record->wmobile;
                        $data['wprofit'] = $record->wprofit;
                    }
                    array_push($excelData, $data);
                }
                break;

            case 'aeps':
            case 'aadharpay':
            case 'cashdeposit':
            case 'matm':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Transaction Id', 'Date','Api Name', 'Provider', 'Bc Id', 'Bc Mobile',' Txnid',' Amount', 'Profit', 'Ref No', 'Status', "Member Name", "Member Mobile"];

                if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Distributor Name", "Distributor Mobile", "Distributor Profit"]);
                }

                if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                    $titles = array_merge($titles, ["MD Name", "MD Mobile", "Md Profit"]);
                }

                if(\Myhelper::hasRole(['whitelable', 'admin'])){
                    $titles = array_merge($titles, ["Whitelable Name", "Whitelable Mobile", "Whitelable Profit"]);
                }

                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['apitype'] = $record->apiname;
                    $data['provider'] = $record->providername;
                    $data['rname'] = $record->aadhar;
                    $data['rmobile'] = $record->mobile;
                    $data['txnid'] = $record->txnid;
                    $data['amount'] = $record->amount;
                    $data['profit'] = $record->charge;
                    $data['refno'] = $record->refno;
                    $data['status'] = $record->status;
                    $data['username'] = $record->username;
                    $data['usermobile'] = $record->usermobile;

                    if(\Myhelper::hasRole(['distributor', 'md', 'whitelable', 'admin'])){
                        $data['disdetailsn'] = $record->disname;
                        $data['disdetailsm'] = $record->dismobile;
                        $data['disprofit'] = $record->disprofit;
                    }

                    if(\Myhelper::hasRole(['md', 'whitelable', 'admin'])){
                        $data['mddetailsn'] = $record->mdname;
                        $data['mddetailsm'] = $record->mdmobile;
                        $data['mdprofit'] = $record->mdprofit;
                    }

                    if(\Myhelper::hasRole(['whitelable', 'admin'])){
                        $data['wdetailsn'] = $record->wname;
                        $data['wdetailsm'] = $record->wmobile;
                        $data['wprofit'] = $record->wprofit;
                    }
                    array_push($excelData, $data);
                }
                break;

            case 'whitelable':
            case 'md':
            case 'distributor':
            case 'retailer':
            case 'retaillite':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Id', 'Date' ,'Name', 'Email', 'Mobile', 'Role', 'Main Balance', 'Aeps Balance', 'Matm Balance', 'Parent Name', 'Parent Mobile', 'RM', 'Company', 'Status' ,'address', 'City', 'State','Pincode','Shopname', 'Gst Tin','Pancard','Aadhar Card', 'Account', 'Bank','Ifsc', 'MD Stock', 'Distributor Stock', 'Retailer Stock'];
                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['name'] = $record->name;
                    $data['email'] = $record->email;
                    $data['mobile'] = $record->mobile;
                    $data['role'] = $record->rolename;
                    $data['mainwallet'] = $record->mainwallet;
                    $data['aepswallet'] = $record->aepswallet;
                    $data['parentsname'] = $record->parentname;
                    $data['parentsmobile'] = $record->parentmobile;
                    $data['RM'] = $record->reference;
                    $data['company'] = $record->companyname;
                    $data['status'] = $record->status;
                    $data['address'] = $record->address;
                    $data['city'] = $record->city;
                    $data['state'] = $record->state;
                    $data['pincode'] = $record->pincode;
                    $data['shopname'] = $record->shopname;
                    $data['gstin'] = $record->gstin;
                    $data['pancard'] = $record->pancard;
                    $data['aadharcard'] = $record->aadharcard;
                    $data['account'] = $record->account;
                    $data['bank'] = $record->bank;
                    $data['ifsc'] = $record->ifsc;
                    $data['mstock'] = $record->mstock;
                    $data['dstock'] = $record->dstock;
                    $data['rstock'] = $record->rstock;
                    array_push($excelData, $data);
                }
            break;

            case 'fundrequest':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Id', 'Date' ,'Paymode', 'Amount', 'Ref No', 'Payment Bank', 'Pay Date', 'Status', 'Requested Via Name', 'Requested Via Mobile', 'Approved By Name', 'Approved By Mobile'];
                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['paymode'] = $record->paymode;
                    $data['amount']   = $record->amount;
                    $data['ref_no']  = $record->ref_no;
                    $data['fundbank'] = $record->fundbank;
                    $data['paydate']  = $record->paydate;
                    $data['status'] = $record->status;
                    $data['username'] = $record->username;
                    $data['usermobile'] = $record->usermobile;
                    $data['senderrname'] = $record->sendername;
                    $data['sendermobile'] = $record->sendermobile;
                    array_push($excelData, $data);
                }
            break;
            
            case 'device':
            case 'retailerid':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Id', 'Date' , 'Amount', 'Ref No', 'Pay Date', 'Status', 'Remark', 'Requested Via Name', 'Requested Via Mobile'];
                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['amount']   = $record->amount;
                    $data['ref_no']  = $record->refno;
                    $data['paydate']  = $record->paydate;
                    $data['status'] = $record->status;
                    $data['remark'] = $record->remark;
                    $data['username'] = $record->username;
                    $data['usermobile'] = $record->usermobile;
                    array_push($excelData, $data);
                }
            break;

            case 'fund':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Order Id', 'Date', 'Payment Type', 'Amount', 'Ref No', 'Status', 'Remarks', 'Requested Via', 'Approved By'];
                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['type'] = $record->product;
                    $data['amount'] = $record->amount;
                    $data['bankref'] = $record->refno;
                    $data['status'] = $record->status;
                    $data['remark'] = $record->remark;
                    $data['userdetails'] = $record->username." (".$record->usermobile.")";
                    $data['senderdetails'] = $record->sendername." (".$record->sendermobile.")";
                    array_push($excelData, $data);
                }
                break;

            case 'aepsfundrequestview':
            case 'aepsfundrequest':
            case 'aepsfundrequestviewall':
            case 'aepsfundrequestviewpayout':
            case 'matmfundrequestview':
            case 'matmfundrequest':
            case 'matmfundrequestviewall':
            case 'matmfundrequestviewpayout':
            case 'aepsfundrequestviewall2020':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Order Id', 'Date', 'Payment Mode', 'Payout Type', 'Payout Id', 'Payout Refno' ,'Account', 'Bank', 'Ifsc', 'Amount', 'Status', 'Username', 'User Mobile'];
                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['type'] = $record->type;
                    $data['paytype'] = $record->pay_type;
                    $data['payid'] = $record->payoutid;
                    $data['payref'] = $record->payoutref;
                    $data['account'] = " ".$record->account."";
                    $data['bank'] = $record->bank;
                    $data['ifsc'] = $record->ifsc;
                    $data['amount'] = $record->amount;
                    $data['status'] = $record->status;
                    $data['userdetails'] = $record->username;
                    $data['usermobuile'] = $record->usermobile;
                    array_push($excelData, $data);
                }
                break;

            case 'aepsagentstatement':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Id', 'Date','BCID' ,'Name', 'Email', 'Phone1', 'Phone2'];
                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['bc_id'] = $record->bc_id;
                    $data['name'] = $record->bc_f_name. " ". $record->bc_l_name." ".$record->bc_l_name;
                    $data['email'] = $record->emailid;
                    $data['phone1'] = $record->phone1;
                    $data['phone2'] = $record->phone2;
                    array_push($excelData, $data);
                }
            break;

            case 'utiid':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Id', 'Date','Vle id','Name', 'Email', 'Mobile', 'Location', 'Contact Person', 'Pincode', 'State', 'Status', 'Remark', 'User Name', 'User Mobile'];
                foreach ($datas as $record) {
                    $data['id'] = $record->id;
                    $data['created_at'] = $record->created_at;
                    $data['vleid'] = $record->vleid;
                    $data['name'] = $record->name;
                    $data['email'] = $record->email;
                    $data['mobile'] = $record->mobile;
                    $data['location'] = $record->location;
                    $data['contact_person'] = $record->contact_person;
                    $data['pincode'] = $record->pincode;
                    $data['state'] = $record->state;
                    $data['status'] = $record->status;
                    $data['remark'] = $record->remark;
                    $data['username'] = $record->username;
                    $data['usermobile'] = $record->usermobile;
                    array_push($excelData, $data);
                }
            break;

            case 'wallet':
            case 'wallet2020':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Date', 'Transaction Id', 'User Details','Product', 'Number', 'ST Type', 'Status', 'Opening Balance', 'Credit', 'Debit'];
                foreach ($datas as $record) {
                    $data['created_at'] = $record->created_at;
                    $data['id'] = $record->id;
                    $data['userdetails'] = $record->username." (".$record->usermobile.")";
                    $data['product'] = $record->product;
                    $data['number'] = $record->number;
                    $data['rtype'] = $record->rtype;
                    $data['status'] = $record->status;
                    $data['balance'] = " ".round($record->balance, 2);
                    if($record->trans_type == "credit"){
                        $data['credit'] = $record->amount + $record->charge - $record->profit;
                        $data['debit']  = '';
                    }elseif($record->trans_type == "debit"){
                        $data['credit'] = '';
                        $data['debit']  = $record->amount + $record->charge - $record->profit;
                    }else{
                        $data['credit'] = '';
                        $data['debit']  = '';
                    }
                    array_push($excelData, $data);
                }
            break;

            case 'awallet':
            case 'matmwallet':
                $name = $type.'report'.date('d_M_Y');
                $titles = ['Date', 'User Details', 'Transaction Details', 'Transaction Type', 'Status', 'Opening Balance', 'Credit', 'Debit'];
                foreach ($datas as $record) {
                    $data['created_at'] = $record->created_at;
                    $data['userdetails'] = $record->username." (".$record->usermobile.")";
                    if($record->transtype == "fund" ){
                        $data['product'] = $record->payid."/".$record->remark;
                    }else{
                        $data['product'] = $record->aadhar."/".$record->mobile."/".$record->refno;
                    }
                    $data['number'] = $record->transtype;
                    $data['status'] = $record->status;
                    $data['balance'] = " ".round($record->balance, 2);
                    if($record->type == "credit"){
                        $data['credit'] = $record->amount + $record->charge;
                        $data['debit']  = '';
                    }elseif($record->type == "debit"){
                        $data['credit'] = '';
                        $data['debit']  = $record->amount + $record->charge;
                    }else{
                        $data['credit'] = '';
                        $data['debit']  = '';
                    }
                    array_push($excelData, $data);
                }
            break;
        }
        
        $excelData = array_merge([$titles], $excelData);
        
        //dd($excelData);
        $export = new \App\Exports\ReportExport($excelData);
        return \Excel::download($export, $name.'.csv');

        // return \Excel::create($name, function ($excel) use ($titles, $excelData) {
        //     $excel->sheet('Sheet1', function ($sheet) use ($titles, $excelData) {
        //         $sheet->fromArray($excelData, null, 'A1', false, false)->prependRow($titles);
        //     });
        // })->download('xls');
    }
}
