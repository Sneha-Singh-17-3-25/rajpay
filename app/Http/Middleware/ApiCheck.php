<?php

namespace App\Http\Middleware;

use Closure;

class ApiCheck
{
    public function handle($request, Closure $next)
    {
        if (!$request->has('token') || empty($request->input('token'))) {
            return response()->json(['statuscode' => 'ERR', 'message' => 'Invalid API token']);
        }

        $token = $request->input('token');

        $user = \App\Model\Apitoken::where('ip', $request->ip())
            ->where('token', $token)
            ->first();

        if (!$user) {
            return response()->json(['statuscode' => 'ERR', 'status' => 'ERR', 'message' => 'Request from invalid IP address']);
        }

        if ($user->status == "0") {
            return response()->json(['statuscode' => 'ERR', 'status' => 'ERR', 'message' => 'IP address approval is pending, kindly contact service provider']);
        }

        $request->merge([
            'via' => 'api',
            'user_id' => $user->user_id
        ]);

        if (!$request->has("lat")) {
            $ip = geoip($request->ip());
            $request->merge([
                'lat' => sprintf('%0.4f', $ip->lat),
                'lon' => sprintf('%0.4f', $ip->lon),
            ]);
        }

        return $next($request);
    }
}
