<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
	protected $fillable = ['refid', 'acc_no', 'ifsccode', 'mobile', 'address', 'city', 'state', "pincode", "merchant_name", "pan", "vpa", "status", "user_id", "merchant_code", "qr_lLink"];

    public $appends = ['username'];

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