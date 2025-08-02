<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\NsdlApiService;

class NsdlApiController extends Controller
{
    protected $nsdlService;

    /**
     * Constructor
     */
    public function __construct(NsdlApiService $nsdlService)
    {
        $this->nsdlService = $nsdlService;
    }

    /**
     * Register a new NSDL agent
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function registerAgent(Request $request)
    {
        return $this->nsdlService->registerAgent($request);
    }

    /**
     * Process a new NSDL account opening
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function openAccount(Request $request)
    {
        return $this->nsdlService->openAccount($request);
    }

    /**
     * Get transaction reports for NSDL accounts
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getReports(Request $request)
    {
        return $this->nsdlService->getReports($request);
    }

    /**
     * Get NSDL agent details by ID
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAgentDetails(Request $request)
    {
        $id = $request->route('id');
        $request->merge(['id' => $id]);
        return $this->nsdlService->getAgentDetails($request);
    }

    /**
     * Update NSDL agent details
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateAgent(Request $request)
    {
        return $this->nsdlService->updateAgent($request);
    }
}