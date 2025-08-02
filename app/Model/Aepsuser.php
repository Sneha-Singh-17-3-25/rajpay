<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Aepsuser extends Model
{
    protected $fillable = ['merchantLoginId','merchantLoginPin','merchantName','merchantAddress','merchantCityName','merchantState','merchantPhoneNumber','userPan','merchantPinCode','merchantAadhar','aadharPic','pancardPic','status','user_id','via', 'merchantEmail', 'merchantShopname','lat','lon', 'url'];

    public $with = ['user'];
    public $appends = ['username'];

    public function user(){
        return $this->belongsTo('App\User');
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
