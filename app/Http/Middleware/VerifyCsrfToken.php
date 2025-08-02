<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'checkaeps/*',
        'pan/*',
        'pan/apply/new',
        'transaction/status',
        'apply/pancard/new',
        'apply/pancard/correction',
        'apply/nsdl/saving_account',
        'account/nsdl/submit',
        'account/nsdl/kyc/submit',
        'nsdl/*',
        'fetch-balance',
        'callback/nsdluat'
    ];
}
