<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = ['name','baseurl','method','requesttype','username','password','mobile','extraparam','operator','amount','txnid','state','refno','payid','message','status','success','pending','failed', 'usernameval', 'passwordval','responsetype','other', 'type'];
}
