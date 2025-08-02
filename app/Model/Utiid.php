<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Utiid extends Model
{
    protected $fillable = ['vleid','payid' ,'txnid' ,'vlepassword' ,'name' ,'location' ,'shopname' ,'pincode' ,'pancard' ,'aadhar' ,'state' ,'email' ,'mobile' ,'type','user_id' ,'api_id' ,'sender_id' ,'status','remark' ,'idtype'];

    public $appends = ['apicode', 'username'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function api(){
        return $this->belongsTo('App\Model\Api');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }
    public function getCreatedAtAttribute($value)
    {
        return date('d M - H:i', strtotime($value));
    }

    public function getApicodeAttribute()
    {
        $data = Api::where('id' , $this->api_id)->first(['code']);
        if($data){
            return $data->code;
        }else{
            return '';
        }
    }

    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") (".$user->role->name.")";
        }
        return $data;
    }
}
