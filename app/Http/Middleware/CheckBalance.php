<?php

namespace App\Http\Middleware;

use Closure;

class CheckBalance
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
        try {
            if($request->has("amount")){
                if(\Request::is("fund/transaction") && $request->has("type") && in_array($request->type, ["request", "return", "qrcode_dynamic", "loadwallet"])){
                    return $next($request);
                }
                
                $user = \DB::table("users")->where("id", $request->user_id)->first(['mainwallet', 'aepswallet', 'lockedamount']);
                if($request->amount > ($user->mainwallet + $user->aepswallet - $user->lockedamount)){
                    return response()->json(['statuscode' => "ERR", "message" => 'Insufficient wallet balance']);
                }
            }
        } catch (\Exception $e) {}

        return $next($request);
    }
}
