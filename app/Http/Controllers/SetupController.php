<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Fundbank;
use App\Model\Api;
use App\Model\Provider;
use App\Model\Apitoken;
use App\Model\PortalSetting;
use App\Model\ServiceManager;
use App\Model\Contact;
use App\Model\Complaintsubject;
use App\Model\Link;
use Illuminate\Validation\Rule;
use App\User;

class SetupController extends Controller
{
    public function index($type, $id=0)
    {
        switch ($type) {
            case 'links':
                $permission = "setup_links";
                break;

            case 'api':
                $permission = "setup_api";
                break;

            case 'apitoken':
                $permission = "setup_apitoken";
                break;

            case 'payoutbank':
                $permission = "setup_payoutbank";
                break;

            case 'bank':
                $permission = "setup_bank";
                break;

            case 'operator':
                $permission = "setup_operator";
                $data['apis'] = Api::where('status', '1')->get(['id', 'product']);
                break;

            case 'servicemanage':
                $permission = "service_manager";
                $data['id'] = $id;
                $data['user'] = User::find($id);
                $data['services'] = ServiceManager::where("user_id", $id)->get();
                $data['apis'] = Api::whereIn('type', ['recharge', 'billpay', 'pancard', 'dmt', 'fund', 'aeps', 'matm', 'payout'])->where('status', '1')->get(['id', 'product']);
                break;
            
            case 'complaintsub':
                $permission = "complaint_subject";
                break;

            case 'portalsetting':
                $data['settlementtype'] = PortalSetting::where('code', 'settlementtype')->first();
                $data['banksettlementtype'] = PortalSetting::where('code', 'banksettlementtype')->first();
                $data['banktransfermode'] = PortalSetting::where('code', 'banktransfermode')->first();
                $data['otplogin'] = PortalSetting::where('code', 'otplogin')->first();
                $data['otpsendmailid']   = PortalSetting::where('code', 'otpsendmailid')->first();
                $data['otpsendmailname'] = PortalSetting::where('code', 'otpsendmailname')->first();
                $data['bcid']   = \App\Model\PortalSetting::where('code', 'bcid')->first();
                $data['cpid']   = \App\Model\PortalSetting::where('code', 'cpid')->first();
                $data['transactioncode']   = \App\Model\PortalSetting::where('code', 'transactioncode')->first();
                $data['mainlockedamount']   = \App\Model\PortalSetting::where('code', 'mainlockedamount')->first();
                $data['aepslockedamount']   = \App\Model\PortalSetting::where('code', 'aepslockedamount')->first();
                $data['impschargeupto25']   = \App\Model\PortalSetting::where('code', 'impschargeupto25')->first();
                $data['impschargeabove25']   = \App\Model\PortalSetting::where('code', 'impschargeabove25')->first();
                $data['neftcharge']= \App\Model\PortalSetting::where('code', 'neftcharge')->first();
                $data['dmttype']   = \App\Model\PortalSetting::where('code', 'dmttype')->first();
                $data['applogout']   = \App\Model\PortalSetting::where('code', 'applogout')->first();
                $data['weblogout']   = \App\Model\PortalSetting::where('code', 'weblogout')->first();
                $data['pincheck']    = \App\Model\PortalSetting::where('code', 'pincheck')->first();
                $data['otppayout']   = \App\Model\PortalSetting::where('code', 'otppayout')->first();
                $permission = "portal_setting";
                break;
            
            default:
                abort(404);
                break;
        }

        if (!\Myhelper::can($permission)) {
            abort(403);
        }
        $data['type'] = $type;

        return view("setup.".$type)->with($data);
    }

    public function update(Request $post)
    {
        switch ($post->actiontype) {
            case 'api':
                $permission = "setup_api";
                break;

            case 'links':
                $permission = "setup_links";
                break;

            case 'apitoken':
                $permission = "setup_apitoken";
                break;

            case 'payoutbank':
                $permission = "setup_payoutbank";
                break;

            case 'bank':
                $permission = "setup_bank";
                break;

            case 'operator':
                $permission = "setup_operator";
                break;

            case 'complaintsub':
                $permission = "complaint_subject";
                break;

            case 'portalsetting':
            case 'slides':
                $permission = "portal_setting";
                break;
                
            case 'banklistupdate':
                $permission = "setup_banklistupdate";
                break;
        }

        if (isset($permission) && !\Myhelper::can($permission)) {
            return response()->json(['status' => "Permission Not Allowed"], 400);
        }

        switch ($post->actiontype) {
            case 'bank':
                $rules = array(
                    'name'    => 'sometimes|required',
                    'account'    => 'sometimes|required|numeric|unique:fundbanks,account'.($post->id != "new" ? ",".$post->id : ''),
                    'ifsc'    => 'sometimes|required',
                    'branch'    => 'sometimes|required'  
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $post['user_id'] = \Auth::id();
                $action = Fundbank::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            case 'api':
                $rules = array(
                    'product' => 'sometimes|required',
                    'name' => 'sometimes|required',
                    'code' => 'sometimes|required|unique:apis,code'.($post->id != "new" ? ",".$post->id : ''),
                    'type' => ['sometimes', 'required'],
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Api::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'apitoken':
                $rules = array(
                    'status'    => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Apitoken::updateOrCreate(['id'=> $post->id], ['status' => $post->status]);
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'payoutbank':
                $rules = array(
                    'status'    => 'required'
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Contact::updateOrCreate(['id'=> $post->id], ['status' => $post->status]);
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'operator':
                $rules = array(
                    'name' => 'sometimes|required',
                    'recharge1' => 'sometimes|required',
                    'recharge2' => 'sometimes|required',
                    'type' => ['sometimes', 'required'],
                    'api_id' => 'sometimes|required|numeric',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Provider::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'servicemanage':
                $rules = array(
                    'api_id' => 'required',
                    'provider_id' => 'required',
                    'user_id' => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = ServiceManager::updateOrCreate(['provider_id'=> $post->provider_id, "user_id" => $post->user_id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'servicemanageall':
                $rules = array(
                    'type'    => 'required',
                    'api_id'  => 'required',
                    'user_id' => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $providers = Provider::where('type', $post->type)->get();

                foreach ($providers as $provider) {
                    ServiceManager::updateOrCreate(['provider_id'=> $provider->id, "user_id" => $post->user_id], $post->all());
                }
                return response()->json(['status' => "success"], 200);
                break;

            case 'complaintsub':
                $rules = array(
                    'subject'    => 'sometimes|required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }

                $action = Complaintsubject::updateOrCreate(['id'=> $post->id], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;

            case 'portalsetting':
                $rules = array(
                    'value'    => 'required',
                    'name'     => 'required',
                    'code'     => 'required',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                if($post->hasFile('slides')){
                    $post['value'] = $post->file('slides')->store('slides');
                }
                $action = PortalSetting::updateOrCreate(['code'=> $post->code], $post->all());;
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
                
            case 'slides':
                $rules = array(
                    'value'    => 'sometimes|required',
                    'name'     => 'required',
                    'code'     => 'required',
                );
                $post['company_id'] = \Auth::user()->company_id;
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                if($post->hasFile('slides')){
                    $post['value'] = asset('public')."/".$post->file('slides')->store('slides');
                }
                $post['name'] = "App Slide ".date('ymdhis');
                $action = PortalSetting::updateOrCreate(['name'=> $post->name], $post->all());
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
                
            case 'banklistupdate':
                $rules = array(
                    'value'    => 'sometimes|required',
                    'name'     => 'required',
                    'code'     => 'required',
                );
                
                switch ($post->value) {
                    case 'aeps':
                        return $this->cwbankupdate();
                        break;

                    case 'aadharpay':
                        return $this->apbankupdate();
                        break;

                    case 'cashdeposit':
                        return $this->cdbankupdate();
                        break;
                }
                break;

            case 'links':
                $rules = array(
                    'name'  => 'required',
                    'value' => 'required|url',
                );
                
                $validator = \Validator::make($post->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 422);
                }
                $action = Link::updateOrCreate(['id'=> $post->id], $post->all());;
                if ($action) {
                    return response()->json(['status' => "success"], 200);
                }else{
                    return response()->json(['status' => "Task Failed, please try again"], 200);
                }
                break;
            
            default:
                # code...
                break;
        }
    }
}
