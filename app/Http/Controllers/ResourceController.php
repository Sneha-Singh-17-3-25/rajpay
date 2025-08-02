<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Scheme;
use App\Model\Company;
use App\Model\Provider;
use App\Model\Commission;
use App\Model\Companydata;
use App\Model\Packagecommission;
use App\Model\Package;
use App\User;

class ResourceController extends Controller
{
    public function index($type)
    {
        $providersType = ["mobile","dth","pancard","dmt","fund","payout","aeps","aadharpay","electricity","gas","lpg","landline","postpaid","broadband","loanrepay","fasttag","cable","insurance",'water'];

        switch ($type) {
            case 'scheme':
                $permission = "scheme_manager";
                $data['commission']['mobile'] = Provider::where('type', 'mobile')->get();
                $data['commission']['dth']    = Provider::where('type', 'dth')->get();
                $data['commission']['mobile-prepaid'] = Provider::where('type', 'prepaid-mobile')->get();
                $data['commission']['dth-prepaid'] = Provider::where('type', 'prepaid-dth')->get();
                $data['commission']['offlinebill'] = Provider::where('type', 'electricity')->where("mode", "offline")->get();
                $data['commission']['electricity'] = Provider::where('type', 'electricity')->where("mode", "online")->get();
                $data['commission']['insurance'] = Provider::whereIn('type', ["lifeinsurance", "healthinsurance"])->where("mode", "online")->get();
                $data['commission']['fasttag']   = Provider::where('type', 'fasttag')->where("mode", "online")->get();
                $data['commission']['water']     = Provider::where('type', 'water')->where("mode", "online")->get();
                $data['commission']['postpaid']  = Provider::whereIn('type', ["mobilepostpaid", "landlinepostpaid", "broadbandpostpaid"])->where("mode", "online")->get();
                $data['commission']['loanrepayment'] = Provider::where('type', 'loanrepayment')->where("mode", "online")->get();
                $data['commission']['gas'] = Provider::where('type', 'gas')->where("mode", "online")->get();
                $data['commission']['lpggas']  = Provider::where('type', 'lpggas')->where("mode", "online")->get();
                $data['commission']['cabletv'] = Provider::where('type', 'cabletv')->where("mode", "online")->get();
                $data['commission']['pancard'] = Provider::where('type', 'pancard')->get();
                $data['commission']['I-Aeps']  = Provider::where('type', 'iaeps')->get();
                $data['commission']['matm']    = Provider::where('type', 'matm')->get();
                
                $data['charge']['dmt'] = Provider::where('type', 'dmt')->get();
                $data['charge']['payout'] = Provider::where('type', 'payout')->get();
                $data['charge']['servicecharge'] = Provider::where('type', 'idcharge')->get();
                break;

            case 'company':
                $permission = "company_manager";
                break;

            case 'companyprofile':
                $permission = "change_company_profile";
                $data['company'] = Company::where('id', \Auth::user()->company_id)->first();
                $data['companydata'] = Companydata::where('company_id', \Auth::user()->company_id)->first();
                break;
            
            case 'commission':
                $permission = "view_commission";

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
                    $data['commission'][$key] = Commission::where('scheme_id', \Auth::user()->scheme_id)->whereHas('provider', function ($q) use($value){
                        $q->whereIn('type' , $value);
                    })->get();
                }
                break;
            
            default:
                # code...
                break;
        }

        if ($type != "package" && !\Myhelper::can($permission)) {
            abort(403);
        }
        $data['type'] = $type;

        return view("resource.".$type)->with($data);
    }

    public function update(Request $post)
    {
        switch ($post->actiontype) {
            case 'scheme':
            case 'commission':
                $permission = "scheme_manager";
                break;
            
            case 'company':
                $permission = ["company_manager", "change_company_profile"];
                break;

            case 'companydata':
                $permission = "change_company_profile";
                break;
        }

        if (isset($permission) && !\Myhelper::can($permission)) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->actiontype) {
            case 'scheme':
                $rules = array(
                    'name'    => 'sometimes|required|unique:schemes,name' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Scheme::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'company':
                $rules = array(
                    'companyname'    => 'sometimes|required'
                );

                if($post->file('logos')){
                    $rules['logos'] = 'sometimes|required|mimes:jpg,JPG,jpeg,png|max:500';
                }
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                if($post->id != 'new'){
                    $company = Company::find($post->id);
                }
                
                if($post->hasFile('file')){
                    try {
                        unlink(public_path('logos/').$company->logo);
                    } catch (\Exception $e) {
                    }
                    if($post->hasFile('file')){
                        $post['logo'] = $post->file('file')->store('logos');
                    }
                }

                $action = Company::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'companydata':
                $rules = array(
                    'company_id'    => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Companydata::updateOrCreate(['company_id'=> $post->company_id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'commission':
                $rules = array(
                    'scheme_id'    => 'sometimes|required|numeric' 
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                foreach ($post->slab as $key => $value) {
                    $update[$value] = Commission::updateOrCreate([
                        'scheme_id' => $post->scheme_id,
                        'slab'      => $post->slab[$key]
                    ],[
                        'scheme_id' => $post->scheme_id,
                        'slab'      => $post->slab[$key],
                        'type'      => $post->type[$key],
                        'whitelable'=> $post->whitelable[$key],
                        'md'   => $post->md[$key],
                        'distributor'=> $post->distributor[$key],
                        'retailer'   => $post->retailer[$key]
                    ]);
                }
                return response()->json(['status'=>$update], 200);
                break;
                
            default:
                # code...
                break;
        }
    }

    public function getCommission(Request $post , $type)
    {
        return Commission::where('scheme_id', $post->scheme_id)->get()->toJson();
    }
}
