<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NpsAccount extends Model
{
	protected $fillable = ['name', 'email', 'mobile', 'district', 'status', 'user_id', 'type', 'panNo'];

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
