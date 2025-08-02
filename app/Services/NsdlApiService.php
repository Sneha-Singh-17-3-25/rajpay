<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Helpers\NsdlApiHelper;

class NsdlApiService
{
    /**
     * Register a new NSDL agent
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function registerAgent(Request $request)
    {
        return NsdlApiHelper::processAgentKyc($request);
    }

    /**
     * Process a new NSDL account opening
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function openAccount(Request $request)
    {
        return NsdlApiHelper::processAccount($request);
    }

    /**
     * Get transaction reports for NSDL accounts
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getReports(Request $request)
    {
        return NsdlApiHelper::getTransactionReports($request);
    }

    /**
     * Get NSDL agent details by ID
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAgentDetails(Request $request)
    {
        return NsdlApiHelper::getAgentDetails($request);
    }

    /**
     * Update NSDL agent details
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateAgent(Request $request)
    {
        return NsdlApiHelper::updateAgent($request);
    }
}