<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\PanCardHelper;

class PanApiController extends Controller
{
    /**
     * Apply for PAN card API endpoint
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyForPan(Request $request)
    {
        return PanCardHelper::applyForPan($request);
    }

    /**
     * Check PAN card application status API endpoint
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPanStatus(Request $request)
    {
        return PanCardHelper::checkPanStatus($request);
    }

    /**
     * Check PAN transaction status API endpoint
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTransactionStatus(Request $request)
    {
        return PanCardHelper::checkTransactionStatus($request);
    }

    /**
     * Get PAN card reports API endpoint
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getPanReports(Request $request)
    {
        return response(PanCardHelper::getPanReports($request))
            ->header('Content-Type', 'application/json');
    }
}