<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fingagent extends Model
{
    protected $fillable = ['merchantLoginId','merchantLoginPin','merchantName','merchantAddress','merchantCityName','merchantState','merchantPhoneNumber','merchantEmail','merchantShopName','userPan','merchantPinCode','merchantAadhar', 'aadharPic','pancardPic','status','via','user_id','remark', 'merchantEmail','lat','lon'];

    public $with = ['user'];

    public $appends = ['username'];

    public function user(){
         return $this->belongsTo('App\User')->select(['id', 'name', 'mobile', 'role_id']);
    }

    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") <br>(".$user->role->name.")";
        }
        return $data;
    }
}
