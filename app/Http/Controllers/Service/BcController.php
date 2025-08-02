<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Circle;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BcController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        // Set the API URL based on environment
        $this->apiUrl = config('services.bcagent.url', 'https://agentbanking.nsdlbank.co.in/bcagentregistrationapi/default.aspx');
    }

    /**
     * Show the BC Agent registration form
     */
    public function index()
    {
        if (\Myhelper::hasNotRole('admin')) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $states = $this->getStates();
        
        // dd($states);
        return view('service.account.agent_registrations', compact('states'));
    }
    public function list()
    {
    // Get all agent registration data
        $agentRegistrations = \DB::table('agent_registrations')->get();
        // dd($agentRegistrations);

    // Format the data according to DataTables structure
        $data = $agentRegistrations->map(function($agent) {
        return [
            'status' => $agent->status,
            'bc_agent_id' => $agent->bc_agent_id,
            'bc_agent_name' => $agent->bc_agent_name,
            'last_name' => $agent->last_name,
            'agent_type' => $agent->agent_type,
            'company_name' => $agent->company_name,
            'address' => $agent->address,
            'area' => $agent->area,
            'district' => $agent->district,
            'city_name' => $agent->city_name,
            'state_name' => $agent->state_name,
            'pincode' => $agent->pincode,
            'mobile_number' => $agent->mobile_number,
            'email' => $agent->email,
            'panNo' => $agent->panNo ?? '',
            'dob' => $agent->dob, 
            'shop_address' => $agent->shop_address,
            'shop_area' => $agent->shop_area,
            'shop_district' => $agent->shop_district,
            'shop_city' => $agent->shop_city,
            'shop_state' => $agent->shop_state,
            'shop_pincode' => $agent->shop_pincode,
            'created_at' => $agent->created_at,
            'updated_at' => $agent->updated_at,
            'created_by_name' => $agent->created_by_name ?? 'Admin',
            'api_response' => $agent->api_response,
        ];
    });

    // Return the data as a JSON response
    return response()->json(['data' => $data]);
}


    /**
     * Handle the BC Agent registration
     */
public function register(Request $request)
{
    try {
        if (\Myhelper::hasNotRole('admin')) {
            return response()->json([
                'status' => 0,
                'description' => 'Unauthorized access'
            ], 403);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'bcagentname' => 'required|string',
            'lastname' => 'required|string',
            'companyname' => 'required|string',
            'address' => 'required|string|min:5|max:200',
            'statename' => 'required|string|min:5|max:49',
            'cityname' => 'required|string|min:5|max:49',
            'district' => 'required|string|min:5|max:49',
            'area' => 'required|string',
            'pincode' => 'required|digits:6',
            'panNo' => 'required',
            'mobilenumber' => 'required|digits:10',
            'emailid' => 'required|email|max:50',
            'dob' => 'required|date_format:d/m/Y',
            'shopaddress' => 'required|string|min:5|max:200',
            'shopstate' => 'required|string|min:5|max:50',
            'shopcity' => 'required|string|min:5|max:50',
            'shopdistrict' => 'required|string|min:5|max:50',
            'shoparea' => 'required|string',
            'shoppincode' => 'required|digits:6',
            'agenttype' => 'required|in:1,2',
            'agentbcid' => 'required|max:15'
        ]);
            
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'description' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate BC Agent ID
        
        $bcAgentId = $request->bc_agent_id;

        // Store in database using Query Builder
     

        // Build XML request
        $xmlRequest = $this->buildXMLRequest($request->all(), $bcAgentId);
   $registrationId = \DB::table('agent_registrations')->insertGetId([
            'bc_agent_id' => $request->bc_agent_id,
            'bc_agent_name' => $request->bcagentname,
            'last_name' => $request->lastname,
            'company_name' => $request->companyname,
            'address' => $request->address,
            'state_name' => $request->statename,
            'city_name' => $request->cityname,
            'district' => $request->district,
            'area' => $request->area,
            'pincode' => $request->pincode,
            'mobile_number' => $request->mobilenumber,
            'email' => $request->emailid,
            'dob' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d'),
            'shop_address' => $request->shopaddress,
            'shop_state' => $request->shopstate,
            'shop_city' => $request->shopcity,
            'shop_district' => $request->shopdistrict,
            'shop_area' => $request->shoparea,
            'shop_pincode' => $request->shoppincode,
            'agent_type' => $request->agenttype,
            'status' => 'pending',
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // Make API call
        $response = $this->makeAPICall($xmlRequest);

        // Process response
        if (is_array($response)) {
            \DB::table('agent_registrations')
                ->where('id', $registrationId)
                ->update([
                    'status' => 'failed',
                    'api_response' => json_encode($response),
                    'updated_at' => now()
                ]);
            return response()->json($response);
        }

        // Parse XML response
        libxml_use_internal_errors(true);
        $xmlResponse = simplexml_load_string($response);

        if ($xmlResponse === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            Log::error('XML Parsing Errors:', ['errors' => $errors]);

            \DB::table('agent_registrations')
                ->where('id', $registrationId)
                ->update([
                    'status' => 'failed',
                    'api_response' => $response,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => 0,
                'description' => 'Failed to parse response',
                'raw_response' => $response
            ]);
        }

        // Check the status in the response and update accordingly
        $status = ((int)$xmlResponse->status === 1) ? 'success' : 'failed';

        // Update database record with API response
        \DB::table('agent_registrations')
            ->where('id', $registrationId)
            ->update([
                'status' => $status,
                'api_response' => $response,
                'updated_at' => now()
            ]);

        // Log the response
        Log::info('BC Agent Registration Response', [
            'request' => $xmlRequest,
            'response' => $response
        ]);

        // Return response based on the parsed status
        return response()->json([
            'status' => (int)$xmlResponse->status,
            'bcagentid' => (string)$xmlResponse->bcagentid,
            'description' => (string)$xmlResponse->description
        ]);

    } catch (\Exception $e) {
        Log::error('BC Agent Registration Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        if (isset($registrationId)) {
            \DB::table('agent_registrations')
                ->where('id', $registrationId)
                ->update([
                    'status' => 'failed',
                    'api_response' => $e->getMessage(),
                    'updated_at' => now()
                ]);
        }

        return response()->json([
            'status' => 0,
            'description' => 'An error occurred while processing your request: '.$e->getMessage()
        ], 500);
    }
}

    public function show()
    {
        return view('statement.bc_registrations');
    }
    /**
     * Build XML request from form data
     */
    protected function makeAPICall($xmlRequest)
    {
        try {
            $client = new Client();

            // Log the actual XML before sending
            Log::info('Raw XML Request:', ['xml' => $xmlRequest]);

            $response = $client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'text/xml',
                    'Accept' => 'text/xml'
                ],
                'body' => $xmlRequest,
                'verify' => false
            ]);

            $responseContent = $response->getBody()->getContents();
            Log::info('Raw API Response:', ['response' => $responseContent]);

            // Check if response is valid XML
            if (!$this->isValidXML($responseContent)) {
                Log::error('Invalid XML Response:', ['response' => $responseContent]);
                return [
                    'status' => 0,
                    'description' => 'Invalid response from server',
                    'raw_response' => $responseContent
                ];
            }

            return $responseContent;

        } catch (\Exception $e) {
            Log::error('API Call Failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    protected function isValidXML($xml) {
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xml);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        return $doc !== false;
    }
/*
 * Build XML request from form data
*/
protected function buildXMLRequest($data, $bcAgentId)
{
    // Create XML with proper encoding declaration
    $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
$xml .= "<bcagentregistrationnewreq>\n";

    // Add BC Partner ID
    $xml .= " <bcid>" . htmlspecialchars($data['agentbcid']) . "</bcid>\n";
    $xml .= " <bcagentid>" . htmlspecialchars($data['bc_agent_id']) . "</bcagentid>\n";

    // Add personal details
    $xml .= '<bcagentname>' . htmlspecialchars($data['bcagentname']) . '</bcagentname>';
    $xml .= '<middlename>' . htmlspecialchars($data['middlename'] ?? '') . '</middlename>';
    $xml .= '<lastname>' . htmlspecialchars($data['lastname']) . '</lastname>';
    $xml .= '<companyname>' . htmlspecialchars($data['companyname']) . '</companyname>';

    // Add address details
    $xml .= '<address>' . htmlspecialchars($data['address']) . '</address>';
    $xml .= '<statename>' . htmlspecialchars($data['statename']) . '</statename>';
    $xml .= '<cityname>' . htmlspecialchars($data['cityname']) . '</cityname>';
    $xml .= '<district>' . htmlspecialchars($data['district']) . '</district>';
    $xml .= '<area>' . htmlspecialchars($data['area']) . '</area>';
    $xml .= '<pincode>' . htmlspecialchars($data['pincode']) . '</pincode>';

    // Add contact details
    $xml .= '<mobilenumber>' . htmlspecialchars($data['mobilenumber']) . '</mobilenumber>';
    $xml .= '<telephone>' . htmlspecialchars($data['telephone'] ?? '') . '</telephone>';
    $xml .= '<alternatenumber>' . htmlspecialchars($data['alternatenumber'] ?? '') . '</alternatenumber>';
    $xml .= '<emailid>' . htmlspecialchars($data['emailid']) . '</emailid>';
    $xml .= '<dob>' . htmlspecialchars($data['dob']) . '</dob>';

    // Add shop details
    $xml .= '<shopaddress>' . htmlspecialchars($data['shopaddress']) . '</shopaddress>';
    $xml .= '<shopstate>' . htmlspecialchars($data['shopstate']) . '</shopstate>';
    $xml .= '<shopcity>' . htmlspecialchars($data['shopcity']) . '</shopcity>';
    $xml .= '<shopdistrict>' . htmlspecialchars($data['shopdistrict']) . '</shopdistrict>';
    $xml .= '<shoparea>' . htmlspecialchars($data['shoparea']) . '</shoparea>';
    $xml .= '<shoppincode>' . htmlspecialchars($data['shoppincode']) . '</shoppincode>';
    $xml .= '<pancard>' . htmlspecialchars($data['panNo']). '</pancard>';
    $xml .= '<bcagentform>' . 'Form123' . '</bcagentform>';

    // Add product details
    $xml .= '<productdetails>';
        $xml .= '<dmt>' . htmlspecialchars($data['dmt'] ?? '0') . '</dmt>';
        $xml .= '<aeps>' . htmlspecialchars($data['aeps'] ?? '0') . '</aeps>';
        $xml .= '<cardpin>' . htmlspecialchars($data['cardpin'] ?? '0') . '</cardpin>';
        $xml .= '<accountopen>' . htmlspecialchars($data['accountopen'] ?? '0') . '</accountopen>';
        $xml .= '</productdetails>';

    // Add terminal details if required
    // if (($data['aeps'] ?? '0') === '1' || ($data['cardpin'] ?? '0') === '1') {
    $xml .= '<terminaldetails>';
        $xml .= '<tposserialno>' . htmlspecialchars($data['tposserialno']) . '</tposserialno>';
        $xml .= '<taddress>' . htmlspecialchars($data['taddress']) . '</taddress>';
        $xml .= '<taddress1>' . htmlspecialchars($data['taddress1'] ?? '') . '</taddress1>';
        $xml .= '<tpincode>' . htmlspecialchars($data['tpincode']) . '</tpincode>';
        $xml .= '<tcity>' . htmlspecialchars($data['tcity'] ?? '') . '</tcity>';
        $xml .= '<tstate>' . htmlspecialchars($data['tstate'] ?? '') . '</tstate>';
        $xml .= '<temail>' . htmlspecialchars($data['temail'] ?? '') . '</temail>';
        $xml .= '</terminaldetails>';
    // }

    // Add agent type details
    $xml .= '<agenttype>' . $data['agenttype'] . '</agenttype>';
    $xml .= '<agentbcid>' . htmlspecialchars($data['agentbcid']) . '</agentbcid>';

    $xml .= "</bcagentregistrationnewreq>";
$xml = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $xml);
Log::info('Final XML Request:', ['xml' => $xml]);
// dd($xml);
return $xml;
}

/**
* Generate unique BC Agent ID
*/

protected function generateBCAgentId()
{
// Implement your logic to generate unique BC Agent ID
// Example: PREFIX + TIMESTAMP + RANDOM
return 'ACH0040' . time() . rand(1000, 9999);
}

/**
* Get states for dropdown
*/

protected function getStates()
{
// Implement your logic to get states
// This could be from a database or static array
return Circle::all();
}
}
