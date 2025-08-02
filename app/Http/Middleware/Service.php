<?php

namespace App\Http\Middleware;

use Closure;

class Service
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
        $request['user_id'] = \Auth::id();
        $request['via'] = "portal";

        if(!$request->has("gps_location") || $request->gps_location == "" || $request->gps_location == null){
            $ip = geoip($request->ip());
            $request['lat'] = $ip->lat;
            $request['lon'] = $ip->lon;
        }else{
            $gpsdata = explode("/", $request->gps_location);
            $request['lat'] = $gpsdata[0];
            $request['lon'] = $gpsdata[1];
        }
        return $next($request);
    }
}
