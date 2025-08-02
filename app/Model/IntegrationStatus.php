<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IntegrationStatus extends Model
{
    protected $fillable = ['api_id','baseurl','method','requesttype','username','password','mobile','operator','amount','txnid','payid','refno','message','status','success','pending','failed', 'usernameval', 'passwordval','responsetype','other'];
}
