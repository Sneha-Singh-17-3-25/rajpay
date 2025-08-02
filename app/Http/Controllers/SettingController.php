<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Circle;
use App\Model\Role;
use File;

class SettingController extends Controller
{
    public function index($id=0)
    {
        if(\Myhelper::hasNotRole('admin') && $id != 0 && (\Auth::id() != $id) && !in_array($id, \Myhelper::getParents(\Auth::id()))){
            abort(401);
        }

        if($id != 0 && (\Auth::id() != $id) && !\Myhelper::can('member_profile_edit')){
            abort(401);
        }
        
        $data = [];
        if($id != 0){
            $data['user'] = User::find($id);
        }else{
            $data['user'] = \Auth::user();
        }

        if(\Myhelper::hasRole('admin')){
            $data['parents'] = User::whereHas('role', function ($q){
                $q->where('slug', '!=', 'retailer');
            })->get(['id', 'name', 'role_id', 'mobile']);

            $data['roles']   = Role::where('slug' , '!=' , 'admin')->get();
        }else{
            $data['parents'] = [];
            $data['roles']   = [];
        }

        $data['state'] = Circle::all(['state']);
        return view('profile.index')->with($data);
    }

    public function profileUpdate(\App\Http\Requests\Member $post)
    {
        if(\Myhelper::hasNotRole(['admin'])){
            unset($post['mobile']);
            unset($post['alternate_mobile']);
            unset($post['mainwallet']);
            unset($post['aepswallet']);
        }

        if(\Myhelper::hasRole(['whitelable', 'md', 'distributor', 'retailer', 'statehead', 'asm'])){
            if((\Auth::id() != $post->id) && !in_array($post->id, \Myhelper::getParents(\Auth::id()))){
                return response()->json(['status' => "Permission Not Alloweds"], 400);
            }
        }

        switch ($post->actiontype) {
            case 'kycdata':
                if((\Auth::id() != $post->id) && !\Myhelper::can('member_profile_edit')){
                    return response()->json(['status' => "Permission Not Alloweds"], 400);
                }
                $user = User::where('id', $post->id)->first();
                $update["kyc"] = "submitted";
                $update["kyctime"] = date("Y-m-d H:i:s");

                if($user->kyc != "verified"){
                    if($post->hasFile('aadharcardpicfronts')){
                        try {
                            unlink(public_path($user->aadharcardpicfront));
                        } catch (\Exception $e) {
                        }

                        $post['aadharcardpicfront'] = $post->file('aadharcardpicfronts')->store('kyc/useronboard'.\Auth::id());
                        $update["aadharcardpicfront"] = $post->aadharcardpicfront;
                    }

                    if($post->hasFile('aadharcardpicbacks')){
                        try {
                            unlink(public_path($user->aadharcardpicback));
                        } catch (\Exception $e) {
                        }

                        $post['aadharcardpicback'] = $post->file('aadharcardpicbacks')->store('kyc/useronboard'.\Auth::id());
                        $update["aadharcardpicback"] = $post->aadharcardpicback;
                    }

                    if($post->hasFile('pancardpics')){
                        try {
                            unlink(public_path($user->pancardpic));
                        } catch (\Exception $e) {
                        }

                        $post['pancardpic'] = $post->file('pancardpics')->store('kyc/useronboard'.\Auth::id());
                        $update["pancardpic"] = $post->pancardpic;
                    }

                    if($post->hasFile('passbooks')){
                        try {
                            unlink(public_path($user->passbook));
                        } catch (\Exception $e) {
                        }

                        $post['passbook'] = $post->file('passbooks')->store('kyc/useronboard'.\Auth::id());
                        $update["passbook"] = $post->passbook;
                    }

                    if($post->hasFile('profiles')){
                        try {
                            unlink(public_path($user->profile));
                        } catch (\Exception $e) {
                        }

                        $post['profile'] = $post->file('profiles')->store('kyc/useronboard'.\Auth::id());
                        $update["profile"] = $post->profile;
                    }

                    if($post->hasFile('otherdocs')){
                        try {
                            unlink(public_path($user->otherdoc));
                        } catch (\Exception $e) {
                        }

                        $post['otherdoc'] = $post->file('otherdocs')->store('kyc/useronboard'.\Auth::id());
                        $update["otherdoc"] = $post->otherdoc;
                    }

                    $response    = User::updateOrCreate(['id'=> $post->id], $update);
                }else{
                    $response = false;
                }
                break;
            
            case 'kycupdate':
                if((\Auth::id() != $post->id) && !\Myhelper::can('member_profile_edit')){
                    return response()->json(['status' => "Permission Not Alloweds"], 400);
                }
                $user = User::where('id', $post->id)->first();

                $update = [
                    "kyc"  => $post->kyc,
                    "shopname"  => $post->shopname,
                    "pancard"   => $post->pancard,
                    "aadharcard"   => $post->aadharcard,
                    "shopaddress"  => $post->shopaddress,
                    "remark"  => $post->remark,
                    'kyc_approval_time' => date("Y-m-d H:i:s"),
                    'kyc_approved_by' => \Auth::user()->name
                ];

                if($post->aadharcardpicfront == ""){
                    try {
                        unlink(public_path($user->aadharcardpicfront));
                    } catch (\Exception $e) {}  
                    $update["aadharcardpicfront"] = NULL;
                }

                if($post->aadharcardpicback == ""){
                    try {
                        unlink(public_path($user->aadharcardpicback));
                    } catch (\Exception $e) {}  
                    $update["aadharcardpicback"] = NULL;
                }

                if($post->pancardpic == ""){
                    try {
                        unlink(public_path($user->pancardpic));
                    } catch (\Exception $e) {}  
                    $update["pancardpic"] = NULL;
                }

                if($post->passbook == ""){
                    try {
                        unlink(public_path($user->passbook));
                    } catch (\Exception $e) {}  
                    $update["passbook"] = NULL;
                }

                if($post->profile == ""){
                    try {
                        unlink(public_path($user->profile));
                    } catch (\Exception $e) {}  
                    $update["profile"] = NULL;
                }

                if($post->otherdoc == ""){
                    try {
                        unlink(public_path($user->otherdoc));
                    } catch (\Exception $e) {}  
                    $update["otherdoc"] = NULL;
                }

                $response = User::updateOrCreate(['id'=> $post->id], $update);
            
                break;

            case 'password':
                if(($post->id != \Auth::id()) && !\Myhelper::can('member_password_reset')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }

                if(($post->id == \Auth::id()) && !\Myhelper::can('password_reset')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }

                if(\Myhelper::hasNotRole('admin')){
                    $credentials = [
                        'mobile'   => \Auth::user()->mobile,
                        'password' => $post->oldpassword
                    ];
            
                    if(!\Auth::validate($credentials)){
                        return response()->json(['errors' =>  ['oldpassword'=>'Please enter corret old password']], 422);
                    }
                }

                $post['password'] = bcrypt($post->password);
                $response = User::where('id', $post->id)->updateOrCreate(['id'=> $post->id], ['password' => $post->password, "resetpwd" => "changed"]);
                break;
                
            case 'memberstatus':
            case 'profile':
                if(($post->id != \Auth::id()) && !\Myhelper::can('member_profile_edit')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }

                if(($post->id == \Auth::id()) && !\Myhelper::can('profile_edit')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }
                $response = User::where('id', $post->id)->updateOrCreate(['id'=> $post->id], $post->all());
                break;
            
            

            case 'mstock' :
            case 'dstock' :
            case 'rstock' :
                if(!\Myhelper::can('member_stock_manager')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }

                if(\Myhelper::hasNotRole(['admin'])){
                    if($post->mstock > 0 && \Auth::user()->mstock < $post->mstock){
                        return response()->json(['status'=>'Low id stock'], 400);
                    }

                    if($post->dstock > 0 && \Auth::user()->dstock < $post->dstock){
                        return response()->json(['status'=>'Low id stock'], 400);
                    }
        
                    if($post->rstock > 0 && \Auth::user()->rstock < $post->rstock){
                        return response()->json(['status'=>'Low id stock'], 400);
                    }
                }

                if($post->mstock != ''){
                    User::where('id', \Auth::id())->decrement('mstock', $post->mstock);
                    $response = User::where('id', $post->id)->increment('mstock', $post->mstock);
                }

                if($post->dstock != ''){
                    User::where('id', \Auth::id())->decrement('dstock', $post->dstock);
                    $response = User::where('id', $post->id)->increment('dstock', $post->dstock);
                }

                if($post->rstock != ''){
                    User::where('id', \Auth::id())->decrement('rstock', $post->rstock);
                    $response = User::where('id', $post->id)->increment('rstock', $post->rstock);
                }
                $response = true;
                break;

            case 'mapping':
                if(\Myhelper::hasNotRole('admin')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }
                $user = User::find($post->id);
                $parent = User::find($post->parent_id);

                if($parent->role->slug == "retailer"){
                    return response()->json(['status' => "Invalid mapping member"], 400);
                }

                switch ($user->role->slug) {
                    case 'retailer':
                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin','distributor', 'md', 'whitelable', 'asm', 'statehead'])->count();
                        break;

                    case 'distributor':
                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin','md', 'whitelable', 'asm', 'statehead'])->count();
                        break;
                    
                    case 'md':
                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin','whitelable', 'asm', 'statehead'])->count();
                        break;

                    case 'whitelable':
                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin', 'asm', 'statehead'])->count();
                        break;

                    case 'asm':
                        $roles = Role::where('id', $parent->role_id)->whereIn('slug', ['admin', 'statehead'])->count();
                        break;

                    default:
                        return response()->json(['status' => "Invalid mapping member"], 400);
                        break;
                }

                if(!$roles){
                    return response()->json(['status' => "Invalid mapping member"], 400);
                }
                $response = User::where('id', $post->id)->updateOrCreate(['id'=> $post->id], ['parent_id' => $post->parent_id]);
                break;

            case 'rolemanager':
                if(\Myhelper::hasNotRole('admin')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }

                $roles = Role::where('id', $post->role_id)->whereIn('slug', ['admin'])->count();
                if($roles){
                    return response()->json(['status' => "Invalid member role"], 400);
                }

                $user = User::find($post->id);
                switch ($user->role->slug) {
                    case 'retailer':
                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['distributor', 'md', 'whitelable', 'statehead', 'asm'])->count();
                        break;

                    case 'distributor':
                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['md', 'whitelable','retailer', 'statehead', 'asm'])->count();
                        break;
                    
                    case 'md':
                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['whitelable','distributor','retailer', 'statehead', 'asm'])->count();
                        break;

                    case 'whitelable':
                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['distributor','retailer','md', 'statehead', 'asm'])->count();
                        break;

                    case 'statehead':
                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['distributor','retailer','md', 'whitelable'])->count();
                        break;

                    case 'asm':
                        $roles = Role::where('id', $post->role_id)->whereIn('slug', ['distributor','retailer','md', 'whitelable', 'statehead'])->count();
                        break;
                }

                if(!$roles){
                    return response()->json(['status' => "Invalid member role"], 400);
                }
                $response = User::where('id', $post->id)->updateOrCreate(['id'=> $post->id], ['role_id' => $post->role_id]);
                break;

            case 'scheme':
                if(\Myhelper::hasNotRole('admin')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                    $users = \Myhelper::getParents($post->id);
                    User::whereIn('id', $users)->where('id', '!=', $post->id)->update(['scheme_id' => $post->scheme_id]);
                }

                $response = User::where('id', $post->id)->updateOrCreate(['id'=> $post->id], ['scheme_id' => $post->scheme_id]);
                break;

            case 'locakedAmount':
                if(!\Myhelper::can('locked_amount')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }

                $response = User::where('id', $post->id)->updateOrCreate(['id'=> $post->id], ['lockedamount' => $post->lockedamount]);
                break;

            case 'kyc_change':
                if(!\Myhelper::can('member_kyc_update')){
                    return response()->json(['status' => "Permission Not Allowed"], 400);
                }
                $response = User::where('id', $post->id)->updateOrCreate(['id'=> $post->id], [
                    'kyc' => $post->kyc, 
                    'kyc_approval_time' => date("Y-m-d H:i:s"),
                    'kyc_approved_by' => \Auth::user()->name
                ]);
                break;
        }
        if($response){
            return response()->json(['status'=>'success'], 200);
        }else{
            return response()->json(['status'=>'fail'], 400);
        }
    }

    public function download_kyc($id)
    {
        if($id != \Auth::id() && !\Myhelper::hasRole("admin")){
            return redirect()->back();
        }

        $zip = new \ZipArchive();
        $fileName = 'user'.$id.'.zip';
        if ($zip->open(public_path($fileName), \ZipArchive::CREATE)== TRUE)
        {
            $files = File::files(public_path('kyc/useronboard' . $id));
            foreach ($files as $key => $value){
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();
        }

        return response()->download(public_path($fileName));
    }
}
