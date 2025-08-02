<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Role;
use App\Model\Circle;
use App\Model\Scheme;
use App\Model\Company;
use App\Model\Provider;
use App\Model\Utiid;
use App\Model\Permission;
use App\User;
use App\Model\Commission;
use App\Model\Packagecommission;
use App\Model\Package;

class MemberController extends Controller
{
    public function index($type , $action="view")
    {
        if($action != 'view' && $action != 'create'){
            abort(404);
        }

        $data['role'] = Role::where('slug', $type)->first();
        $data['roles'] = [];
        if(!$data['role'] && !in_array($type, ['other', 'web', 'kycpending', 'kycsubmitted', 'kycrejected'])){
            abort(404);
        }
        
        if($action == "view" && !\Myhelper::can('view_'.$type)){
            abort(401);
        }elseif($action == "create" && !\Myhelper::can('create_'.$type) && !in_array($type, ['kycpending', 'kycsubmitted', 'kycrejected'])){
            abort(401);
        }

        if($action == "create"){
            $roles = Role::whereIn('slug', ["statehead", "asm", "whitelable", "md", 'distributor', 'retailer'])->get();

            foreach ($roles as $role) {
                if(\Myhelper::can('create_'.$type)){
                    $data['roles'][] = $role;
                }
            }

            $roless = Role::whereNotIn('slug', ['admin', "statehead", "asm", "whitelable", "md", 'distributor', 'retailer'])->get();

            foreach ($roless as $role) {
                if(\Myhelper::can('create_other')){
                    $data['roles'][] = $role;
                }
            }
        }
        
        if ($action == "create" && (!$data['role'] && sizeOf($data['roles']) == 0)){
            abort(404);
        }
        
        $data['type']   = $type;
        $data['state']  = Circle::all();
        $data['scheme'] = Scheme::where('user_id', \Auth::id())->get();
        if(\Myhelper::hasRole('admin')){
            $data['companys'] = Company::all(['id', 'companyname']);
        }
        
        $types = array(
            'Resource' => 'resource',
            'Setup Tools' => 'setup',
            'Member'   => 'member',
            'Member Setting'   => 'memberaction',
            'Member Report'    => 'memberreport',
            'Wallet Fund'   => 'fund',
            'Wallet Fund Report' => 'fundreport',
            'Aeps Fund'     => 'aepsfund',
            'Aeps Fund Report'   => 'aepsfundreport',
            'Agents List'   => 'idreport',
            'Portal Services'    => 'service',
            'User Setting'  => 'setting',
            'Transactions'  => 'report',
            'Transactions Status' => 'reportstatus'
        );
        
        foreach ($types as $key => $value) {
            $data['permissions'][$key] = Permission::where('type', $value)->orderBy('id', 'ASC')->get();
        }

        if($action == "view"){
            return view('member.index')->with($data);
        }else{
            return view('member.create')->with($data);
        }
    }

    public function completeProfile()
    {
        return view("profile.completeKyc");
    }

    public function create(\App\Http\Requests\Member $post)
    {
        \LogActivity::addToLog('Member-create', $post);
        
        $role = Role::where('id', $post->role_id)->first();
        if(!\Myhelper::can('create_'.$role->slug)){
            return response()->json(['status' => "Permission not allowed"],200);
        }

        $pan = \DB::table("users")->where("pancard", $post->pancard)->where("role_id", $role->id)->first();
        if($pan){
            return response()->json(['status' => "Pancard already used"],200);
        }

        $aadhar = \DB::table("users")->where("aadharcard", $post->aadharcard)->where("role_id", $role->id)->first();
        if($aadhar){
            return response()->json(['status' => "Pancard already used"],200);
        }

        $post['id'] = "new";
        $post['kyc'] = "pending";
        $post['password'] = bcrypt($post->mobile);

        if(\Myhelper::hasNotRole('admin')){
            $post['company_id'] = \Auth::user()->company_id;
        }

        $maxid = User::max('id');
        $post['agentcode'] = \Auth::user()->company->companycode.($maxid+1001);

        if($post->hasFile('aadharcardpics')){
            $filename ='addhar'.\Auth::id().date('ymdhis').".".$post->file('aadharcardpics')->guessExtension();
            $post->file('aadharcardpics')->move(public_path('kyc/'), $filename);
            $post['aadharcardpic'] = $filename;
        }

        if($post->hasFile('pancardpics')){
            $filename ='pan'.\Auth::id().date('ymdhis').".".$post->file('pancardpics')->guessExtension();
            $post->file('pancardpics')->move(public_path('kyc/'), $filename);
            $post['pancardpic'] = $filename;
        }

        if (!$post->has('scheme_id')) {
            $scheme = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $post->role_id)->first();
            if($scheme){
                $post['scheme_id'] = $scheme->permission_id;
            }
        }

        $response = User::updateOrCreate(['id'=> $post->id], $post->all());
    	if($response){
            $permissions = \DB::table('default_permissions')->where('type', 'permission')->where('role_id', $post->role_id)->get();
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission) {
                    $insert = array('user_id'=> $response->id , 'permission_id'=> $permission->permission_id);
                    $inserts[] = $insert;
                }
                \DB::table('user_permissions')->insert($inserts);
            }
    		return response()->json(['status'=>'success'], 200);
    	}else{
    		return response()->json(['status'=>'fail'], 400);
    	}
    }

    public function getCommission(Request $post)
    {
        $product = [
            'Recharge' => ['mobile', 'dth'],
            'Bbps'     => ['electricity', 'water', 'gas', 'postpaid', 'landline', 'broadband', 'insurance', 'cable', 'gasutility', 'housing', 'lifeinsurance', 'loanrepay', 'muncipal', 'fasttag'],
            'Pancard'  => ['pancard'],
            'Dmt'      => ['dmt'],
            'Aeps'     => ['iaeps'],
            'Matm'     => ['matm'],
            "Payout"   => ['payout']
        ];

        foreach ($product as $key => $value) {
            $data['commission'][$key] = Commission::where('scheme_id', $post->scheme_id)->whereHas('provider', function ($q) use($value){
                $q->whereIn('type' , $value);
            })->get();
        }
        return response()->json(view('member.commission')->with($data)->render());
    }



    public function getparents(Request $post)
    {
        $parentData = session('parentData');
        $role = Role::where('id', $post->roleid)->first(['slug']);

        if(!\Myhelper::can('create_'.$role->slug)){
            return response()->json(['status'=>'ERR', 'message' => "Permission Not Allowed"], 400);
        }

        switch (\Auth::user()->role->slug) {
            case 'whitelable':
                if($role->slug == "retailer"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }elseif($role->slug == "distributor"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer', 'distributor']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }elseif($role->slug == "md"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer', 'distributor', 'md']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }
                break;

            case 'md':
                if($role->slug == "retailer"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }elseif($role->slug == "distributor"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer', 'distributor']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }
                break;

            case 'distributor':
                if($role->slug == "retailer"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }
                break;
            
            default:
                if($role->slug == "retailer"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }elseif($role->slug == "distributor"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer', 'distributor']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }elseif($role->slug == "md"){
                    $user = User::whereIn('id', $parentData)->whereHas('role', function ($q){
                        $q->whereNotIn('slug', ['retailer', 'distributor', 'md']);
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }else{
                    $user = User::whereHas('role', function ($q){
                        $q->where('slug', 'admin');
                    })->with('role')->get(['id', 'name', 'role_id' ]);
                }
                break;
        }
        $data['data']   = $user;
        $data['status'] = "success";
        return response()->json($data, 200);
    }
}
