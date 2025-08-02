<?php

namespace App\Http\Middleware;

use Closure;

class PinCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Request::is("fund/transaction") && $request->has("type") && in_array($request->type, ["request", "qrcode_dynamic"])){
            return $next($request);
        }
        
        if(\Request::is("payout/transaction")){
            return $next($request);
        }

        try {
            if($request->has("amount")){
                $pincheck = \DB::table('portal_settings')->where('code', "pincheck")->first();
                if($pincheck){
                    if($pincheck->value == "yes"){
                        if(!\Myhelper::can('pin_check')){
                            $code = \DB::table('pindatas')->where('user_id', $request->user_id)->where('pin', \Myhelper::encrypt($request->pin, "vppay##01012022"))->first();
                            if(!$code){
                                return response()->json(['statuscode' => "ERR", "message" => "Transaction pin is incorrect"]);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {}
        return $next($request);
    }
}
