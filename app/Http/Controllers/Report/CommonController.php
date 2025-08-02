<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Utiid;
use Carbon\Carbon;
use App\Model\Report;
use App\Model\Aepsreport;
use App\User;
use App\Model\Provider;
use App\Model\Api;
use App\Model\PortalSetting;
use Illuminate\Validation\Rule;
use Firebase\JWT\JWT;

class CommonController extends Controller
{
    protected $api, $admin;
    public function __construct()
    {
        $this->api = Api::where('code', 'dmt2')->first();
        $this->admin = User::whereHas('role', function ($q){
            $q->where('slug', 'admin');
        })->first();
    }
    
    public function fetchData(Request $request, $type, $id=0, $returntype="all")
	{
		$request['return'] = 'all';
		$request['returntype'] = $returntype;

		if(\Myhelper::hasNotRole("admin")){
			$parentid = \Auth::id();
		}else{
			$parentid = $this->admin->id;
		}

		switch ($type) {
		    case 'setuplinks':
				$request['table']= '\App\Model\Link';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','desc'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
				
			case 'loginsessions':
				$request['table'] = '\App\Model\Loginsession';
				$request['searchdata'] = ['user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DSEC'];
				$request['parentData'] = 'all';
			break;
			
		    case 'websession':
				$request['table']= '\App\Model\Session';
				$request['searchdata'] = ['user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;

			case 'appsession':
				$request['table']= '\App\Model\Securedata';
				$request['searchdata'] = ['user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
				
			case 'permissions':
				$request['table']= '\App\Model\Permission';
				$request['searchdata'] = ['name', 'slug'];
				$request['select'] = 'all';
				$request['order'] = ['id','DSEC'];
				$request['parentData'] = 'all';
			break;

			case 'roles':
				$request['table']= '\App\Model\Role';
				$request['searchdata'] = ['name', 'slug'];
				$request['select'] = 'all';
				$request['order'] = ['id','DSEC'];
				$request['parentData'] = 'all';
			break;

			case 'apilogs':
				$request['table'] = 'apilogs';
				$request['dbtype']= 'apilogs';
				$request['searchdata'] = ['txnid'];
				$request['select'] = 'all';
				$request['order'] = ['id','DSEC'];
				$request['parentData'] = 'all';
			break;

			case 'whitelable':
			case 'md':
			case 'distributor':
			case 'retailer':
			case 'apiuser':
			case 'asm':
			case 'other':
			case 'tr' :
			case 'kycpending':
			case 'kycsubmitted':
			case 'kycrejected':
			case 'web':
				$request['table']  = '\App\User';
				$request['searchdata'] = ['name', 'mobile','email', 'id', 'agentcode'];
				$request['select'] = 'all';
				$request['order']  = ['id','DESC'];
				if (\Myhelper::hasRole(['whitelable', 'md', 'distributor', 'retailer'])){
					$request['parentData'] = \Myhelper::getParents(\Auth::id());
				}else{
					$request['parentData'] = 'all';
				}
				$request['whereIn'] = 'parent_id';
			break;

			case 'fundrequest':
				$request['table']   = '\App\Model\Fundreport';
				$request['searchdata'] = ['amount','ref_no', 'remark','paymode', 'user_id'];
				$request['select']  = 'all';
				$request['order']   = ['id','DESC'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;
			
			case 'fundrequestview':
			case 'fundrequestviewall':
				$request['table']   = '\App\Model\Fundreport';
				$request['searchdata'] = ['amount','ref_no', 'remark','paymode', 'user_id'];
				$request['select']  = 'all';
				$request['order']   = ['id','DESC'];
				$request['parentData'] = [$parentid];
				$request['whereIn'] = 'credited_by';
				break;

			case 'fundstatement':
				$request['table']= '\App\Model\Report';
				$request['searchdata'] = ['amount','number', 'mobile','credit_by', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setupbank':
				$request['table']= '\App\Model\Fundbank';
				$request['searchdata'] = ['name','account', 'ifsc','branch'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setupapi':
				$request['table']= '\App\Model\Api';
				$request['searchdata'] = ['name','account', 'ifsc','branch'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
				
			case 'setupoperator':
				$request['table']= '\App\Model\Provider';
				$request['searchdata'] = ['name','recharge1', 'recharge2','type'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
				
			case 'setupservicemanage':
				$request['table']= '\App\Model\ServiceManager';
				$request['searchdata'] = ['provider_id', 'api_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setupcomplaintsub':
				$request['table']= '\App\Model\Complaintsubject';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setupapitoken':
				$request['table']= '\App\Model\Apitoken';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
			
			case 'apitoken':
				$request['table']= '\App\Model\Apitoken';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;
			
			case 'setuppayoutbank':
				$request['table']= '\App\Model\Contact';
				$request['searchdata'] = ['name', 'account'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;
				
			case 'loginslide':
				$request['table']= '\App\Model\PortalSetting';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = ['slides'];
				$request['whereIn'] = 'code';
				break;
				
			case 'appslide':
				$request['table']= '\App\Model\PortalSetting';
				$request['searchdata'] = ['name'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = ['appslides'];
				$request['whereIn'] = 'code';
				break;

			case 'resourcescheme':
				$request['table']= '\App\Model\Scheme';
				$request['searchdata'] = ['name', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;

			case 'resourcepackage':
				$request['table']= '\App\Model\Package';
				$request['searchdata'] = ['name', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = [\Auth::id()];
				$request['whereIn'] = 'user_id';
				break;

			case 'resourcecompany':
				$request['table']= '\App\Model\Company';
				$request['searchdata'] = ['companyname'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				$request['parentData'] = 'all';
				$request['whereIn'] = 'user_id';
				break;

			case 'complaints':
				$request['table']= '\App\Model\Complaint';
				$request['searchdata'] = ['type', 'solution', 'description', 'user_id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole(['retailer', 'apiuser', 'retaillite'])){
							$request['parentData'] = [\Auth::id()];
						}elseif(\Myhelper::hasRole(['md', 'distributor','whitelable'])){
							$request['parentData'] = \Myhelper::getParents(\Auth::id());
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, \Myhelper::getParents(\Auth::id()))){
							$request['parentData'] = [$id];
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'paepsagentstatement':
				$request['table']= '\App\Model\Aepsuser';
				$request['searchdata'] = ['merchantPhoneNumber','merchantLoginId'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole([['whitelable', 'md', 'distributor', 'retailer']])){
							$request['parentData'] = \Myhelper::getParents(\Auth::id());
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, \Myhelper::getParents(\Auth::id()))){
							$request['parentData'] = [$id];
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
				
			case 'faepsagentstatement':
				$request['table']= '\App\Model\Fingagent';
				$request['searchdata'] = ['id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole([['whitelable', 'md', 'distributor', 'retailer']])){
							$request['parentData'] = \Myhelper::getParents(\Auth::id());
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, \Myhelper::getParents(\Auth::id()))){
							$request['parentData'] = [$id];
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
				
			case 'utiidstatement':
			case 'nsdlidstatement':
				$request['table']= '\App\Model\Utiid';
				$request['searchdata'] = ['id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole([['whitelable', 'md', 'distributor', 'retailer']])){
							$request['parentData'] = \Myhelper::getParents(\Auth::id());
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, \Myhelper::getParents(\Auth::id()))){
							$request['parentData'] = [$id];
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;
				
			case 'npsstatement':
				$request['table']= '\App\Model\NpsAccount';
				$request['searchdata'] = ['id'];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if ($id == 0 || $returntype == "all") {
					if($id == 0){
						if (\Myhelper::hasRole([['whitelable', 'md', 'distributor', 'retailer']])){
							$request['parentData'] = \Myhelper::getParents(\Auth::id());
						}else{
							$request['parentData'] = 'all';
						}
					}else{
						if(in_array($id, \Myhelper::getParents(\Auth::id()))){
							$request['parentData'] = [$id];
						}else{
							$request['parentData'] = [\Auth::id()];
						}
					}
					$request['whereIn'] = 'user_id';
				}else{
					$request['parentData'] = [$id];
					$request['whereIn'] = 'id';
					$request['return'] = 'single';
				}
				break;

			case 'payoutaccount':
				$request['table']= '\App\Model\Contact';
				$request['searchdata'] = [];
				$request['select'] = 'all';
				$request['order'] = ['id','DESC'];
				if(\Myhelper::hasNotRole("admin")){
					$request['parentData'] = [\Auth::id()];
				}else{
					$request['parentData'] = 'all';
				}
				$request['whereIn'] = 'user_id';
				break;

			default:
				break;
        }
        
		$request['where']=0;
		$request['type']= $type;
        
		try {
			$totalData = $this->getData($request, 'count');
		} catch (\Exception $e) {
			$totalData = 0;
		}

		if ((isset($request->searchtext) && !empty($request->searchtext)) ||
           	(isset($request->todate) && !empty($request->todate))       ||
           	(isset($request->product) && !empty($request->product))       ||
           	(isset($request->status) && $request->status != '')		  ||
           	(isset($request->agent) && !empty($request->agent))
         ){
	        $request['where'] = 1;
	    }

		try {
			$totalFiltered = $this->getData($request, 'count');
		} catch (\Exception $e) {
			$totalFiltered = 0;
		}
		//return $data = $this->getData($request, 'data');
		try {
			$data = $this->getData($request, 'data');
		} catch (\Exception $e) {
			$data = [];
		}
		
		if ($request->return == "all" || $returntype =="all") {
			$json_data = array(
				"draw"            => intval( $request['draw'] ),
				"recordsTotal"    => intval( $totalData ),
				"recordsFiltered" => intval( $totalFiltered ),
				"data"            => $data
			);
			echo json_encode($json_data);
		}else{
			return response()->json($data);
		}
	}

	public function getData($request, $returntype)
	{ 
		$table = $request->table;
		if($request->has("dbtype")){
			$data = \DB::table($table);
		}else{
			$data = $table::query();
		}
		$data->orderBy($request->order[0], $request->order[1]);

		if($request->parentData != 'all'){
			if(!is_array($request->whereIn)){
				$data->whereIn($request->whereIn, $request->parentData);
			}else{
				$data->where(function ($query) use($request){
					$query->where($request->whereIn[0] , $request->parentData)
					->orWhere($request->whereIn[1] , $request->parentData);
				});
			}
		}

		if( $request->type != "roles" &&
			$request->type != "permissions" &&
			$request->type != "fundrequestview" &&
			$request->type != "fundrequest" &&
			$request->type != "setupbank" &&
			$request->type != "setupapi" &&
			$request->type != "setuppayoutbank" &&
			$request->type != "setupoperator" &&
			$request->type != "setupapitoken" &&
			$request->type != "resourcescheme" &&
			$request->type != "resourcecompany" &&
			$request->type != "websession" &&
			$request->type != "appsession" &&
			$request->type != "payoutaccount" &&
			$request->type != "setupcomplaintsub" &&
			$request->type != "npsstatement" &&
			$request->type != "setuplinks" &&
			!in_array($request->type , [ 'whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'other', 'tr', 'web', 'kycpending', 'kycsubmitted', 'kycrejected'])&&
			$request->where != 1
        ){
            if(!empty($request->fromdate)){
                $data->whereDate('created_at', $request->fromdate);
            }
	    }

        switch ($request->type) {
			case 'apiuser':
			case 'asm':
			case 'whitelable':
			case 'md':
			case 'distributor':
			case 'retailer':
			case 'apiuser':
				$data->whereHas('role', function ($q) use($request){
					$q->where('slug', $request->type);
				})->whereIn('kyc', ['verified']);
			break;

			case 'web':
				$data->where('type' ,'web');
			break;

			case 'other':
				$data->whereHas('role', function ($q) use($request){
					$q->whereNotIn('slug', [ 'whitelable', 'md', 'distributor', 'retailer', 'apiuser', 'admin']);
				})->whereIn('kyc', ['verified']);
			break;

			case 'tr':
				$data->whereHas('role', function ($q) use($request){
					$q->whereIn('slug', [ 'whitelable', 'md', 'distributor', 'retailer', 'apiuser']);
				})->where('kyc', 'verified');
			break;

			case 'kycpending':
				$data->whereHas('role', function ($q) use($request){
					$q->whereNotIn('slug', [ 'admin']);
				})->whereIn('kyc', ['pending']);
			break;

			case 'kycsubmitted':
				$data->whereHas('role', function ($q) use($request){
					$q->whereNotIn('slug', [ 'admin']);
				})->whereIn('kyc', ['submitted']);
			break;
				
			case 'kycrejected':
				$data->whereHas('role', function ($q) use($request){
					$q->whereNotIn('slug', [ 'admin']);
				})->whereIn('kyc', ['rejected']);
			break;

			case 'fundrequest':
				$data->where('type', 'request');
				break;

			case 'fundrequestview':
				$data->where('status', 'pending')->where('type', 'request');
				break;
			
			case 'fundrequestviewall':
				$data->where('type', 'request');
				break;
			
			case 'utiidstatement':
				$data->where('idtype', 'uti');
				break;
			
			case 'nsdlidstatement':
				$data->where('idtype', 'nsdl');
				break;
        }

		if ($request->where) {
	        if((isset($request->fromdate) && !empty($request->fromdate)) 
	        	&& (isset($request->todate) && !empty($request->todate))){
	        	    
	        	if(!in_array($request->type, ['websession', 'appsession'])){
	            if($request->fromdate == $request->todate){
	                $data->whereDate('created_at','=', Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'));
	            }else{
	                $data->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $request->fromdate)->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $request->todate)->addDay(1)->format('Y-m-d')]);
	            }
	        	}
	        }

	        if(isset($request->product) && !empty($request->product)){
	            switch ($request->type) {
					case 'setupoperator':
	            		$data->where('type', $request->product);
					break;

					case 'complaints':
	            		$data->where('product', $request->product);
					break;
				}
			}
			
	        if(isset($request->status) && $request->status != '' && $request->status != null){
	        	switch ($request->type) {	
					case 'kycpending':
					case 'kycsubmitted':
					case 'kycrejected':
						$data->where('kyc', $request->status);
					break;

					default:
	            		$data->where('status', $request->status);
					break;
				}
			}
			
			if(isset($request->agent) && !empty($request->agent)){
	        	switch ($request->type) {
					case 'whitelable':
					case 'md':
					case 'distributor':
					case 'retailer':
					case 'apiuser':
					case 'asm':
					case 'other':
					case 'tr' :
					case 'kycpending':
					case 'kycsubmitted':
					case 'kycrejected':
					case 'web':
						$data->whereIn('id', $this->agentFilter($request));
					break;

					default:
						$data->whereIn('user_id', [$request->agent]);
					break;
				}
	        }

	        if(!empty($request->searchtext)){
	            $data->where( function($q) use($request){
	            	foreach ($request->searchdata as $value) {
	            		$q->orWhere($value, 'like',$request->searchtext.'%');
                  		$q->orWhere($value,'like','%'.$request->searchtext.'%');
                  		$q->orWhere($value, 'like','%'.$request->searchtext);
	            	}
				});
	        } 
      	}
		
		if ($request->return == "all" || $request->returntype == "all") {
			if($returntype == "count"){
				return $data->count();
			}else{
				if($request['length'] != -1){
					$data->skip($request['start'])->take($request['length']);
				}

				if($request->select == "all"){
					return $data->get();
				}else{
					return $data->select($request->select)->get();
				}
			}
		}else{
			if($request->select == "all"){
				return $data->first();
			}else{
				return $data->select($request->select)->first();
			}
		}
	}

	public function agentFilter($post)
	{
		if (\Myhelper::hasRole('admin') || in_array($post->agent, session('parentData'))) {
			return \Myhelper::getParents($post->agent);
		}else{
			return [];
		}
	}

    public function delete(Request $post)
    {
        if (\Myhelper::hasNotRole(['admin', 'whitelable'])) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }
        
        switch ($post->type) {
            case 'slide':
                try {
                    \Storage::delete($post->slide);
                } catch (\Exception $e) {}
                $action = true;
                if ($action) {
                    PortalSetting::where('value', $post->slide)->delete();
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'appsession':
                $action = \DB::table('securedatas')->where('id', $post->id)->delete();
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'websession':
                $action = \DB::table('sessions')->where('tid', $post->id)->delete();
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'payoutbank':
                $action = \DB::table('contacts')->where('id', $post->id)->delete();
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            default:
                return response()->json(['status' => "Permission Not Allowed"], 400);
                break;
        }        
    }

	public function update(Request $post)
    {
        switch ($post->actiontype) {
			case 'paepsid':
			case 'faepsid':
                $permission = "aepsid_statement_edit";
				break;

			case 'utiid':
                $permission = "utiid_statement_edit";
				break;
				
			case 'recharge':
                $permission = "recharge_statement_edit";
				break;
				
			case 'billpay':
			case 'offlinebillpay':
                $permission = "billpay_statement_edit";
				break;
			
			case 'nsdl':	
			case 'pancard':
			case 'directpancard':
                $permission = "pancard_statement_edit";
				break;
			
			case 'dmt':
                $permission = "dmt_statement_edit";
                break;

			case 'aeps':
			case 'matm':
			case 'payout':
                $permission = "adminaeps_statement_edit";
				break;
        }

        if (!\Myhelper::can($permission)) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->actiontype) {
            case 'faepsid':
                $rules = array(
					'id'     => 'required',
                    'status' => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = \App\Model\Fingagent::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype', 'remark', 'user_id', 'gps_location', 'lat', 'lon', 'via']));
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;

            case 'utiid':
                $rules = array(
					'id'     => 'required',
                    'status' => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = \App\Model\Utiid::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype', 'remark', 'user_id', 'gps_location', 'lat', 'lon', 'via']));
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;

			case 'paepsid':
                $rules = array(
					'id'     => 'required',
                    'status' => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = \App\Model\Aespuser::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype', 'remark', 'user_id', 'gps_location', 'lat', 'lon', 'via']));
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
					
			case 'recharge':
			case 'billpay':
			case 'dmt':
			case 'pancard':
			case 'offlinebillpay':
                $rules = array(
					'id'     => 'required',
                    'status' => ['sometimes', 'required', Rule::In(['pending', 'success', 'reversed'])],
                    'txnid'  => 'required',
					'refno'  => 'required',
                    'payid'  => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Report::where('id', $post->id)->first();
				if(!$report || !in_array($report->status , ['pending', 'success'])){
					return response()->json(['status' => "Recharge Editing Not Allowed"], 400);
				}

                $action = Report::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype', 'gps_location', 'lat', 'lon', 'user_id', 'via']));
                if ($action) {
					if($post->status == "reversed"){
						\Myhelper::transactionRefund($post->id);
					}
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
        
            case 'nsdl':
			case 'directpancard':
                $rules = array(
					'id'     => 'required',
                    'status' => ['sometimes', 'required', Rule::In(['pending', 'success', 'reversed', 'refund_pending'])],
                    'txnid'  => 'required',
					'refno'  => 'required',
                    'payid'  => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Report::where('id', $post->id)->first();

				if(!$report || !in_array($report->status , ['pending', 'refund_pending', 'incomplete', 'reversed', 'refunded'])){
					return response()->json(['status' => "Recharge Editing Not Allowed"], 400);
				}

                $action = Report::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype','remark', 'gps_location', 'lat', 'lon', 'user_id', 'via']));

                if ($action) {
					if($post->status == "reversed"){
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
                            $failedData = array(            
                                "MERCHANTCODE" => "MODISH2022",
                                "SSOTOKEN"     => "0",
                                "REQUESTID"    => $report->txnid,
                                "EMITRATOKEN"  => $report->payid,
                                "CHECKSUM"     => $checkSumcurl["response"],
                                "ENTITYTYPEID" => "2",
                                "CANCELREMARK" => $report->remark
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
                            if(isset($cancleData->CANCELSTATUS) && $cancleData->CANCELSTATUS != "SUCCESS"){
                                Report::where('id', $post->id)->update([
                                    "status" => "refund_pending"
                                ]);
                            }else{
                            	Report::where('id', $post->id)->update([
                                    "status" => "refunded"
                                ]);
                            }
                        }
					}
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;

			case 'payout':
                $rules = array(
					'id'     => 'required',
                    'status' => ['sometimes', 'required', Rule::In(['pending', 'success', 'reversed'])],
                    'txnid'  => 'required',
					'refno'  => 'required',
                    'payid'  => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Aepsreport::where('id', $post->id)->first();
				if(!$report || !in_array($report->status , ['pending', 'success', 'accept'])){
					return response()->json(['status' => "Transaction Editing Not Allowed"], 400);
				}

                $action = Aepsreport::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype', 'gps_location', 'lat', 'lon', 'user_id', 'via']));
                if ($action) {
					if($post->status == "reversed"){
						\Myhelper::transactionRefundAeps($post->id);
					}
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
				
			case 'aeps':
			case 'matm':
                $rules = array(
					'id'    => 'required',
                    'status'=> ['sometimes', 'required', Rule::In(['failed', 'complete', 'pending', 'success'])],
                    'txnid' => 'required',
					'refno' => 'required',
                    'payid' => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
				}

				$report = Aepsreport::where('id', $post->id)->first();
                $action = Aepsreport::where('id', $post->id)->update($post->except(['id', '_token', 'actiontype', 'gps_location', 'lat', 'lon', 'user_id', 'via']));
                if ($action) {
                	
					if(in_array($report->status, ["pending", "initiated"]) && $post->status == "complete"){
                        $user = User::where('id', $report->user_id)->first();
                        $insert = [
                            "mobile" => $report->mobile,
                            "number" => $report->number,
                            "api_id" => $report->api_id,
                            "provider_id" => $report->provider_id,
                            "txnid"  => $report->txnid,
                            "refno"  => "Txnid - ".$report->id. " Cleared",
                            "amount" => $report->amount,
                            "charge" => $report->charge,
                            "profit" => $report->profit,
                            "tds" => $report->tds,
                            "user_id" => $report->user_id,
                            "balance" => $user->aepswallet,
                            'option1' => $report->option1,
                            'option2' => $report->option2,
                            "option3" => $report->option3,
                            'option4' => $report->option4,
                            'option5' => $report->option5,
                            'option6' => $report->option6,
                            'option7' => $report->option7,
                            'option8' => $report->option8,
                            'via' => $report->via,
                            'status'  => 'success',
                            'payid'   => $report->payid,
                            'credit_by'  => $report->credit_by,
                            'trans_type' => 'credit',
                            'product' => $post->actiontype
                        ];
                        
                        if($report->option1 == "CW"){
                            $action = User::where('id', $report->user_id)->increment('aepswallet', $report->amount + ($report->profit - $report->tds));
                        }elseif($report->option1 == "M"){
                            $action = User::where('id', $report->user_id)->increment('aepswallet', $report->amount - $report->charge);
                        }else{
                            $action = false;
                        }
                        
                        if($action){
                            $aeps = Aepsreport::create($insert);
                            if($report->amount > 0 && $report->option1 == "CW"){
                                \Myhelper::commission(Aepsreport::find($aeps->id));
                            }
                            
                            if($report->amount > 99 && $report->option1 == "M"){
                                \Myhelper::commission(Aepsreport::find($aeps->id));
                            }
                        }
                    }
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
				break;
        }
	}
	
	public function status(Request $post)
    {
		if (!\Myhelper::can($post->type."_status")) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
		}

		switch ($post->type) {
			case 'recharge':
			case 'billpayment':
			case 'money':
			case 'pancard':
				$report = Report::where('id', $post->id)->first();
				
				if(Carbon::parse($report->created_at)->timestamp > Carbon::now()->subMinutes(5)->timestamp){
				    return response()->json(['status' => "Permission Not Allowed"], 400);
				}
				break;

			case 'directpancard':
				$report = Report::where('id', $post->id)->first();
				
				if(Carbon::parse($report->created_at)->timestamp > Carbon::now()->subMinutes(30)->timestamp){
				    return response()->json(['status' => "Permission Not Allowed"], 400);
				}

				if($report->status == "pending"){
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

			        $url = "https://admin.e-banker.in/api/nsdl/signature?type=T&data=".base64_encode(json_encode($parameterData));
			        $signature = \Myhelper::curl($url, "POST", "", [], "no");
			        $data      = substr(json_decode(json_encode($signature["response"]), true), 52);

			        $url = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/checkTransactionStatus";
			        $response = \Myhelper::curl($url, "POST", $data, array(
			            "cache-control: no-cache",
			            "content-type: application/json",
			            "postman-token: 9a6f7632-b4ea-78f1-c393-12a31b12316a"
			          ), "no");

			        $pandata = json_decode($response["response"]);
                	dd($pandata);
			        if(isset($pandata->status) && $pandata->status == "success"){
			            return response()->json(['statuscode' => "TXN", "message" => "Your acknowledgement number is ".$pandata->ack_No]);
			        }elseif(isset($pandata->error) && $pandata->error != null){
			            return response()->json(['statuscode' => "ERR", "message" => isset($pandata->error) ? json_encode($pandata->error) : "Something went wrong"]);
			        }else{
			            return response()->json(['statuscode' => "ERR", "message" => isset($pandata->errordesc) ? $pandata->errordesc : "Something went wrong"]);
			        }
				}
        		return response()->json(['status' => "Permission Not Allowed"], 400);
				break;
				
			case 'aeps':
			case 'matm':
			case 'payout':
				$report = Aepsreport::where('id', $post->id)->first();
				
				if(Carbon::parse($report->created_at)->timestamp > Carbon::now()->subMinutes(5)->timestamp){
				    return response()->json(['status' => "Permission Not Allowed"], 400);
				}
				break;

			default:
				return response()->json(['status' => "Status Not Allowed"], 400);
				break;
		}

		if(!$report || !in_array($report->status , ['pending', 'success', 'approved', 'accepted', 'initiated'])){
			return response()->json(['status' => "Transaction Status Not Allowed"], 400);
		}

		if($post->type == "aeps" && (!$report || !in_array($report->status , ['pending', 'success']))){
			return response()->json(['status' => "Aeps Status Not Allowed"], 400);
		}
		
		if($post->type == "matm" && (!$report || !in_array($report->status , ['pending', 'initiated']))){
			return response()->json(['status' => "Matm Status Not Allowed"], 400);
		}

		$api = \App\Model\Api::where("id", $report->api_id)->first();
		switch ($post->type) {
			case 'recharge':
			case 'billpayment':
			case 'money':
			case 'matm':
				$url    = $api->url;
				$method = "POST";
				$parameter = json_encode([
					'token'    => $api->username,
					'apitxnid' => $report->txnid,
				]);
				
                $header = array(
                    "Content-Type: application/json"
                );
				break;
				
			case 'aeps':
				if($report->option1 == "CW"){
                    $url = $report->api->url."aeps/aepsquery/query";
                }else{
                    $url = $report->api->url."aadharpay/aadharpayquery/query";
                }
                $method = "POST";
                $parameters = array(
                    'reference' => $report->txnid
                );
                
                $key = $api->optional2;
                $iv  = $api->optional3;
                $cipher   = openssl_encrypt(json_encode($parameters,true), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                $request  = base64_encode($cipher);
                $parameter  = http_build_query(array('body' => $request));
                
                $gpsdata = geoip($post->ip());
                $payload =  [
                    "timestamp" => time(),
                    "partnerId" => $api->username,
                    "reqid"     => $report->user_id.Carbon::now()->timestamp
                ];

                $token = JWT::encode($payload, $api->password);

                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/x-www-form-urlencoded",
                    "Token: ".$token,
                    "Authorisedkey: ".$api->optional1
                );
				break;
				
			case 'payout':
                $url = $report->api->url."status";
                $method = "POST";
                $parameter = json_encode(array(
                    'refid' => $report->txnid,
                    'ackno' => $report->payid,
                ));
                
                $payload =  [
                    "timestamp" => time(),
                    "partnerId" => $api->username,
                    "reqid"     => $report->user_id.Carbon::now()->timestamp
                ];

                $token = JWT::encode($payload, $api->password);
                $header = array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "Token: ".$token,
                    "Authorisedkey: ".$api->optional1
                );
				break;

			case "pancard":
				$method = "POST";
                $header = array(
		            "content-type: application/json"
		        );

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

		        $urls = "https://admin.e-banker.in/api/nsdl/signature?type=T&data=".base64_encode(json_encode($parameterData));
		        $signature = \Myhelper::curl($urls, "POST", "", [], "no");
		        $parameter = substr(json_decode(json_encode($signature["response"]), true), 52);

		        $url = "https://assisted-service.egov-nsdl.com/SpringBootFormHandling/checkTransactionStatus";
				break;

			default:
				break;
		}

		$result = \Myhelper::curl($url, $method, $parameter, $header);
		
		if($result['response'] != ''){
			switch ($post->type) {
				case 'recharge':
					$doc = json_decode($result['response']);

					if(isset($doc->statuscode) && $doc->statuscode == "TXN")
						if(isset($doc->data->status) && strtolower($doc->data->status) == "1"){
							$update['refno'] = $doc->data->operatorid;
							$update['status'] = "success";
						}elseif(isset($doc->data->status) && strtolower($doc->data->status) == "0"){
							$update['status'] = "reversed";
							$update['refno'] = (isset($doc->message)) ? $doc->message : "failed";
						}else{
							$update['status'] = "Unknown";
							$update['refno'] = (isset($doc->message)) ? $doc->message : "Unknown";
						}
					$product = "recharge";
					break;

				case 'billpayment':
					$doc = json_decode($result['response']);
					switch ($report->api->code) {
    					case 'billpay1':
						    \DB::table('rp_log')->insert([
                                'ServiceName' => "Billpay-Status",
                                'header' => json_encode($header),
                                'body' => json_encode($parameter),
                                'response' => $result['response'],
                                'url' => $url,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                            
							$doc = json_decode($result['response']);
							if(isset($doc->data->status) && strtolower($doc->data->status) == "1"){
								$update['refno'] = $doc->data->operatorid;
								$update['status'] = "success";
							}elseif(isset($doc->data->status) && strtolower($doc->data->status) == "0"){
								$update['status'] = "reversed";
								$update['refno'] = (isset($doc->message)) ? $doc->message : "failed";
							}else{
								$update['status'] = "Unknown";
								$update['refno'] = (isset($doc->message)) ? $doc->message : "Unknown";
							}
							break;
    				}
    				$product = "billpay";
					break;

				case 'pancard':
					$data =json_decode($result['response']);
                    if(isset($data->status) && $data->status == "success"){
                        $update['status'] = "success";
                        $update['refno']  = $data->ack_No;
                    }elseif(isset($data->status) && in_array($data->status, ["failure", 'AUTO-CLOSED'])){
                        $update['status'] = "reversed";
                        $update['refno']  = "Failed";
                        $update['remark'] = isset($data->errordesc) ? $data->errordesc : "Failed";
                    }elseif(isset($data->status) && in_array($data->status, ["NA"]) && isset($data->error->ER066)){
                        $update['status'] = "reversed";
                        $update['refno']  = "Failed";
                        $update['remark'] = $data->error->ER066;
                    }else{
                        $update['status'] = "Unknown";
                    }
					break;

				case 'money':
					$data = json_decode($result['response']);
                    if(isset($data->txn_status) && in_array($data->txn_status, ["5"])){
                        $update['status'] = "refund";
                        $update['refno']  = $data->message;
                    }elseif(isset($data->txn_status) && in_array($data->txn_status, ["1"])){
                        $update['status'] = "success";
                        $update['refno'] = isset($data->utr) ? $data->utr : "success";
                        $update['payid'] = isset($data->ackno) ? $data->ackno : "success";
                    }elseif(isset($data->txn_status) && in_array($data->txn_status, ["0"])){
                        $update['status'] = "reversed";
                        $update['refno']  = $data->message;
                    }else{
                        $update['status'] = "Unknown";
                    }

					$product = "money";
					break;

				case 'utiid':
					$doc = json_decode($result['response']);
					//dd($doc);
					if(isset($doc->statuscode) && $doc->statuscode == "TXN"){
						$update['status'] = "success";
						$update['remark'] = $doc->message;
					}elseif(isset($doc->statuscode) && $doc->statuscode == "TXF"){
						$update['status'] = "reversed";
						$update['remark'] = $doc->message;
					}elseif(isset($doc->statuscode) && $doc->statuscode == "TUP"){
						$update['status'] = "pending";
						$update['remark'] = $doc->message;
					}else{
						$update['status'] = "Unknown";
					}
					$product = "utiid";
					break;
					
				case 'aeps':
					$doc = json_decode($result['response']);
					
					\DB::table('rp_log')->insert([
                        'ServiceName' => "Aeps-Status",
                        'header' => json_encode($header),
                        'body'   => json_encode($parameters),
                        'response'    => $result['response'],
                        'url'         => $url,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);
                    
					if(isset($doc->status) && isset($doc->txnstatus)){
                        if($doc->status == true && $doc->txnstatus == "1"){
                            $update['status'] = "complete";
                            $update['refno']  = $doc->bankrrn;
                            $update['payid']  = $doc->ackno;
                        }elseif($doc->status == true && $doc->txnstatus == "3"){
                            $update['status'] = "failed";
                            $update['refno']  = $doc->bankrrn;
                            $update['payid']  = $doc->ackno;
                        }else{
                            $update['status'] = "Unknown";
                            $update['refno']  = $doc->message;
                        }
                    }else{
                        $update['status'] = "Unknown";
                    }
					$product = "aeps";
					break;

				case 'payout':
					\DB::table('rp_log')->insert([
                        'ServiceName' => "Payout-Status",
                        'header' => json_encode($header),
                        'body'   => json_encode($parameter),
                        'response'    => $result['response'],
                        'url'         => $url,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);

					$doc = json_decode($result['response']);
                    if(isset($doc->status) && isset($doc->data->txn_status)){
                        if($doc->status == true && $doc->data->txn_status == "1"){
                            $update['status'] = "success";
                            $update['refno']  = $doc->data->utr;
                        }elseif($doc->status == true && $doc->data->txn_status == "0"){
                            $update['status'] = "reversed";
                            $update['refno']  = $doc->data->utr;
                        }else{
                            $update['status'] = "Unknown";
                            $update['refno']  = $doc->message;
                        }
                    }else{
                        $update['status'] = "Unknown";
                    }
                    break;
			}

			if (in_array($update['status'], ["success", "complete", "failed", "reversed", "pending", 'approved','rejected', 'refund'])) {
				switch ($post->type) {
					case 'recharge':
					case 'billpayment':
					case 'pancard':
					case 'money':
						$reportupdate = Report::where('id', $post->id)->update($update);
						if ($reportupdate && $update['status'] == "reversed") {
							\Myhelper::transactionRefund($post->id);
						}
						break;

					case 'payout':
						$reportupdate = Aepsreport::where('id', $post->id)->update($update);
						if ($reportupdate && $update['status'] == "reversed") {
							\Myhelper::transactionRefundAeps($post->id);
						}
						break;

                    case 'aeps':
						$reportupdate = Aepsreport::where('id', $post->id)->update($update);
						
						if($report->status == "pending" && $update['status'] == "complete"){
						    $user = User::where('id', $report->user_id)->first();
                            $insert = [
                                "mobile" => $report->mobile,
                                "number" => $report->number,
                                "api_id" => $report->api_id,
                                "provider_id" => $report->provider_id,
                                "txnid"  => $report->txnid,
                                "refno"  => "Txnid - ".$report->id. " Cleared",
                                "amount" => $report->amount,
                                "charge" => $report->charge,
                                "profit" => $report->profit,
                                "tds" => $report->tds,
                                "user_id" => $report->user_id,
                                "balance" => $user->aepswallet,
                                'option1' => $report->option1,
                                'option2' => $report->option2,
                                "option3" => $report->option3,
                                'option4' => $report->option4,
                                'option5' => $report->option5,
                                'option6' => $report->option6,
                                'option7' => $report->option7,
                                'option8' => $report->option8,
                                'via' => $report->via,
                                'status'  => 'success',
                                'payid'   => $report->payid,
                                'credit_by'  => $report->credit_by,
                                'trans_type' => 'credit',
                                'product' => "aeps"
                            ];
                            
                            if($report->option1 == "CW"){
                                $action = User::where('id', $report->user_id)->increment('aepswallet', $report->amount + ($report->profit - $report->tds));
                            }elseif($report->option1 == "M"){
                                $action = User::where('id', $report->user_id)->increment('aepswallet', $report->amount - $report->charge);
                            }else{
                                $action = false;
                            }
                            
                            if($action){
                                $aeps = Aepsreport::create($insert);
                                if($report->amount > 500 && $report->option1 == "CW"){
                                    \Transaction::commission(Aepsreport::find($aeps->id));
                                }
                                
                                if($report->amount > 99 && $report->option1 == "M"){
                                    \Transaction::commission(Aepsreport::find($aeps->id));
                                }
                            }
						}
						
						if($report->option1 == "CW"){
                            if($update['status'] == "complete"){
                                $this->raepsthreewayrecon($report->txnid, "success", $report->user_id);
                            }else{
                                $this->raepsthreewayrecon($report->txnid, "failed", $report->user_id);
                            }
                        }
						break;

					case 'utiid':
						$reportupdate = Utiid::where('id', $post->id)->update($update);
						break;
				}
			}
			return response()->json($update, 200);
		}else{
			return response()->json(['status' => "Status Not Fetched , Try Again."], 400);
		}
	}
	
    public function getPasswordHashEncode($data){

        $enc_data = hash("sha256",$data,true);
        
        for($i=0;$i<5;$i++){
            $enc_data = hash("sha256",$enc_data,true);
        } 
        return base64_encode($enc_data);
    }
    
    public function getAccessToken($post)
    {
        $url = "https://api.rapipay.com/auth";
        $header = array(
            'Content-Type: application/json',
            "authorization: Basic ".$this->api->optional1
        ); 
        
        $parameter = [
            "serviceType"     => "ValidCredentialService",
            "agentId"         => $this->api->username,
            "password"        => $this->getPasswordHashEncode($this->api->password),
            "clientRequestIP" => $post->ip(),
            "requestType"     => "handset_CHannel",
            "nodeAgentId"   => $this->api->username,
            "imeiNo"        => md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . $this->api->username),
            "domainName"    => "e-banker.in",
            "txnRefId"      => \Auth::id().date('ymdhis'),
            "typeMobileWeb" => "WEB"
        ];
        
        $enc_data = hash_hmac('sha512', json_encode($parameter), $parameter['imeiNo']);
        
        $parameter['checkSum'] = $enc_data;
        
        $aeps = Aepstransaction::where('user_id', \Auth::id())->first();

        $output = "error";
        if( !$aeps || 
            ((Carbon::now()->timestamp - Carbon::createFromFormat('Y-m-d H:i:s', $aeps->created_at)->timestamp) >= $aeps->expires_in))
        {
            $result = \Myhelper::curl($url, 'POST', json_encode($parameter), $header, 'yes' , 'DMT2', $parameter['txnRefId']);  
            
            //dd([json_encode($parameter), $enc_data, $parameter, $result]);
            if($result['response'] != ''){
                $data = json_decode($result['response']);
                if(isset($data->responseCode) && $data->responseCode == "200"){
                    $output = Aepstransaction::create([
                        'user_id' => \Auth::id(),
                        'access_token'=> $data->sessionKey,
                        'token_type'=> $data->sessionRefNo,
                        'expires_in'=> time()
                    ]);
                }
            }
        }else{
            $output = $aeps;
        }
        return $output;
    }

    public function checkSumGenerate($data)
    {
        $str = json_encode($data);
        $enc_data = hash_hmac('sha512', $str, $this->api->username);
        return $enc_data;
    }
    
    public function getToken($uniqueid, $partnerId, $key)
    {
        $payload =  [
            "timestamp" => time(),
            "partnerId" => $partnerId,
            "reqid"     => $uniqueid
        ];
        
        $key = $key;
        $signer = new HS256($key);
        $generator = new JwtGenerator($signer);
        return ['token' => $generator->generate($payload), 'payload' => $payload];
    }
}
