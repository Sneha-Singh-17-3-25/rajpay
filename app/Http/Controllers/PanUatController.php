<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;
use DataTables;
class PanUatController extends Controller {
    /**
     * Base URL for API endpoints
     */
    // protected $baseUrl = 'https://preprod.assisted-service.egov-nsdl.com/SpringBootFormHandling';
    protected $baseUrl = 'https://preprod.assisted-service.egov.proteantech.in/SpringBootFormHandling';
    // protected $baseUrl = 'https://assisted-service.egov-nsdl.com/SpringBootFormHandling';
// https://preprod.assisted-service.egov.proteantech.in/SpringBootFormHandling

 
    /**
     * Display the balance check form
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
       return view("panuat");
    }

    /**
     * Generate a unique transaction ID
     * 
     * @return string
     */
    private function generateTransactionId()
    {
        return 'ACH' . time() . rand(1000, 9999);
    }

    /**
     * Log API request and response to database
     * 
     * @param string $endpoint
     * @param array $requestData
     * @param mixed $response
     * @return void
     */
    private function logTransaction($endpoint, $requestData, $response)
    {
        try {
            DB::table('uatlogs')->insert([
                'endpoint' => $endpoint,
                'request_data' => json_encode($requestData),
                'response_data' => is_string($response) ? $response : json_encode($response),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (Exception $e) {
            Log::error('Failed to log transaction: ' . $e->getMessage());
        }
    }

    /**
     * Process a new PAN application request
     *
     * @param Request $request
     * @return mixed
     */
    public function newPanRequest(Request $request)
    {
        // Build form data from request or use defaults
        $formData = [
            "applicantDto" => [
                "appliType" => $request->input('appliType', 'A'),
                "category" => $request->input('category', 'A'),
                "title" => $request->input('title', 0),
                "lastName" => $request->input('lastName', ''),
                "firstName" => $request->input('firstName', ''),
                "middleName" => $request->input('middleName', ''),
                "dob" => $request->input('dob', ''),
                "gender" => $request->input('gender', 'N'),
                "consent" => $request->input('consent', false),
                "nameOnPanCard" => $request->input('nameOnPanCard', ''),
                "knownByOtherName" => $request->input('knownByOtherName', "\u0000"),
                "otherTitle" => $request->input('otherTitle', "0"),
                "otherLastName" => $request->input('otherLastName', ''),
                "otherFirstName" => $request->input('otherFirstName', ''),
                "otherMiddleName" => $request->input('otherMiddleName', ''),
                "applnMode" => $request->input('applnMode', "\u0000"),
                "nameAsPerAadhar" => $request->input('nameAsPerAadhar', '')
            ],
            "parentsDetails" => [
                "singleParent" => $request->input('singleParent', "\u0000"),
                "fatherLastName" => $request->input('fatherLastName', ''),
                "fatherFirstName" => $request->input('fatherFirstName', ''),
                "fatherMiddleName" => $request->input('fatherMiddleName', ''),
                "motherLastName" => $request->input('motherLastName', ''),
                "motherFirstName" => $request->input('motherFirstName', ''),
                "motherMiddleName" => $request->input('motherMiddleName', ''),
                "parentNamePrint" => $request->input('parentNamePrint', "\u0000")
            ],
            "otherDetails" => [
                "phyPanIsReq" => $request->input('phyPanIsReq', "\u0000"),
                "sourceOfIncome" => [
                    "salary" => $request->input('salary', false),
                    "otherSource" => $request->input('otherSource', false),
                    "businessProf" => $request->input('businessProf', false),
                    "businessPrfCode" => $request->input('businessPrfCode', "0"),
                    "noIncome" => $request->input('noIncome', false),
                    "housePro" => $request->input('housePro', false),
                    "capitalGains" => $request->input('capitalGains', false)
                ],
                "addrForCommunication" => $request->input('addrForCommunication', "none"),
                "officeAddress" => [
                    "flatNo" => $request->input('officeFlatNo', ''),
                    "nameOfPremises" => $request->input('officeNameOfPremises', ''),
                    "road" => $request->input('officeRoad', ''),
                    "area" => $request->input('officeArea', ''),
                    "town" => $request->input('officeTown', ''),
                    "countryName" => $request->input('officeCountryName', "none"),
                    "state" => $request->input('officeState', "none"),
                    "pinCode" => $request->input('officePinCode', ''),
                    "zipCode" => $request->input('officeZipCode', ''),
                    "officeName" => $request->input('officeName', '')
                ],
                "isdCode" => $request->input('isdCode', ''),
                "stdCode" => $request->input('stdCode', '0141'),
                "telOrMobNo" => $request->input('telOrMobNo', ''),
                "emailId" => $request->input('emailId', ''),
                "place" => $request->input('place', ''),
                "date" => $request->input('date', ''),
                "residenceAddress" => [
                    "rflatNo" => $request->input('rflatNo', ''),
                    "rnameOfPremises" => $request->input('rnameOfPremises', ''),
                    "rroad" => $request->input('rroad', ''),
                    "rarea" => $request->input('rarea', ''),
                    "rtown" => $request->input('rtown', ''),
                    "rcountryName" => $request->input('rcountryName', "none"),
                    "rstate" => $request->input('rstate', "none"),
                    "rpinCode" => $request->input('rpinCode', ''),
                    "rzipCode" => $request->input('rzipCode', '')
                ],
                "raValue" => $request->input('raValue', "\u0000"),
                "raTitle" => $request->input('raTitle', "0"),
                "raLastName" => $request->input('raLastName', ''),
                "raFirstName" => $request->input('raFirstName', ''),
                "raMiddleName" => $request->input('raMiddleName', ''),
                "raAddress" => [
                    "raFlatNo" => $request->input('raFlatNo', ''),
                    "raNameOfPremises" => $request->input('raNameOfPremises', ''),
                    "raRoad" => $request->input('raRoad', ''),
                    "raArea" => $request->input('raArea', ''),
                    "raTown" => $request->input('raTown', ''),
                    "raCountryName" => $request->input('raCountryName', "none"),
                    "raState" => $request->input('raState', "none"),
                    "raPinCode" => $request->input('raPinCode', ''),
                    "raZipCode" => $request->input('raZipCode', '')
                ]
            ],
            "aoCode" => [
                "areaCode" => $request->input('areaCode', ''),
                "aoType" => $request->input('aoType', ''),
                "rangeCode" => $request->input('rangeCode', ''),
                "aoNo" => $request->input('aoNo', '')
            ]
        ];
        
        // Encode form data
        $formDataEncoded = base64_encode(json_encode($formData));
       
        // Prepare request entity data
        $reqEntityData = [
            "txnid" => $request->input('txnid', $this->generateTransactionId()),
            "reqTs" => now()->subMinutes(5)->format('Y-m-d\TH:i:s'),
            "entityId" => $request->input('entityId', "AchariyaUAT"),
            "branchCode" => $request->input('branchCode', "branchcode"),
            "dscProvider" => $request->input('dscProvider', "Verasys CA 2014"),
            "dscSerialNumber" => $request->input('dscSerialNumber', "2B 32 60 77"),
            "dscExpiryDate" => $request->input('dscExpiryDate', "4-3-24"),
            "returnUrl" => $request->input('returnUrl', "https:\\\\preprod.assisted-service.egov-nsdl.com\\PanApplication\\panRes"),
            "authKey" => $request->input('authKey', "AchariyaUAT")
        ];
        
        if ($formDataEncoded) {
            $reqEntityData["formData"] = $formDataEncoded;
        }
        
        // Make API request
        $reqJson = $this->makeApiRequest('newPanReq', $reqEntityData, 'F');
        
        // Log transaction
        $this->logTransaction('newPanReq', $reqEntityData, $reqJson);
        
        // Return view for redirection
        return view('pan.redirect', [
            'reqJson' => $reqJson, 
            'url' => $this->baseUrl . '/newPanReq'
        ]);
    }

    /**
     * Process an incomplete PAN application request
     *
     * @param Request $request
     * @return mixed
     */
    public function incompleteApplication(Request $request)
    {
        // Prepare request entity data for incomplete application
        $reqEntityData = [
            "incomplete_txn_id" => $request->input('incomplete_txn_id', $this->generateTransactionId()),
            "txn_id" => $request->input('txn_id', $this->generateTransactionId()),
            "entityId" => $request->input('entityId', "AchariyaUAT"),
            "branchCode" => $request->input('branchCode', "branchcode"),
            "returnUrl" => $request->input('returnUrl', "https:\\\\preprod.assisted-service.egov-nsdl.com\\PanApplication\\panRes"),
            "reqTs" => now()->subMinutes(5)->format('Y-m-d\TH:i:s'),
            "dscProvider" => $request->input('dscProvider', "PROTEAN"),
            "dscSerialNumber" => $request->input('dscSerialNumber', "123123123"),
            "dscExpiryDate" => $request->input('dscExpiryDate', "03/10/2023"),
            "authKey" => $request->input('authKey', "AchariyaUAT")
        ];

        // Make API request
        $reqJson = $this->makeApiRequest('incompleteApplication', $reqEntityData, 'I');
        
        // Log transaction
        $this->logTransaction('incompleteApplication', $reqEntityData, $reqJson);
        
        // Return view for redirection
        return view('pan.redirect', [
            'reqJson' => $reqJson, 
            'url' => $this->baseUrl . '/incompleteApplication'
        ]);
    }

    /**
     * Process a PAN status request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function panStatusReq(Request $request)
    {
        // Prepare request entity data for PAN status
        $reqEntityData = [
            "txnid" => $request->input('txnid', $this->generateTransactionId()),
            "reqTs" => now()->subMinutes(5)->format('Y-m-d\TH:i:s'),
            "ackNo" => $request->input('ackNo', '990019700070973'),
            "entityId" => $request->input('entityId', "AchariyaUAT"),
            "dscProvider" => $request->input('dscProvider', "PROTEAN"),
            "dscSerialNumber" => $request->input('dscSerialNumber', "123213131"),
            "dscExpiryDate" => $request->input('dscExpiryDate', "03/10/2023"),
            "returnUrl" => $request->input('returnUrl', "https:\\\\preprod.assisted-service.egov-nsdl.com\\PanApplication\\panRes"),
            "authKey" => $request->input('authKey', "AchariyaUAT")
        ];

        // Make API request
        $reqJson = $this->makeApiRequest('PanStatusReq', $reqEntityData, 'S');
        
        // Log transaction
        $this->logTransaction('PanStatusReq', $reqEntityData, $reqJson);
        
        // Return JSON response
        return response()->json([
            'status' => 'success',
            'data' => json_decode($reqJson, true)
        ]);
    }

    /**
     * Check transaction status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTransactionStatus(Request $request)
    {
        $requestType = $request->input('request_type', 'T');
        
        // Prepare request entity data based on request type
        $reqEntityData = [
            "request_type" => $requestType,
            "entity_Id" => $request->input('entity_Id', "AchariyaUAT"),
            "returnUrl" => $request->input('returnUrl', "https:\\\\preprod.assisted-service.egov-nsdl.com\\PanApplication\\panRes"),
            "reqTs" => now()->subMinutes(5)->format('Y-m-d\TH:i:s'),
            "dscProvider" => $request->input('dscProvider', "PROTEAN"),
            "dscSerialNumber" => $request->input('dscSerialNumber', "123213131"),
            "dscExpiryDate" => $request->input('dscExpiryDate', "03/10/2023"),
            "authKey" => $request->input('authKey', "AchariyaUAT")
        ];

        // Add type-specific fields
        if ($requestType === 'T') {
            $reqEntityData["txn_id"] = $request->input('txn_id', $this->generateTransactionId());
            $reqEntityData["unique_txn_id"] = $request->input('unique_txn_id', $this->generateTransactionId());
            $reqEntityData["date"] = "";
        } else {
            $reqEntityData["txn_id"] = "";
            $reqEntityData["unique_txn_id"] = $request->input('unique_txn_id', $this->generateTransactionId());
            $reqEntityData["date"] = $request->input('date', now()->format('d-m-Y'));
        }

        // Make API request
        $reqJson = $this->makeApiRequest('checkTransactionStatus', $reqEntityData, 'T');
        
        // Log transaction
        $this->logTransaction('checkTransactionStatus', $reqEntityData, $reqJson);
        
        // Return JSON response
        return response()->json([
            'status' => 'success',
            'data' => json_decode($reqJson, true)
        ]);
    }

    /**
     * Check entity balance
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkBalance(Request $request)
    {
        // Prepare request entity data for balance check
    //     $reqEntityData = [
    //       "entityId" => "AchariyaUAT",
    // "dscProvider" => "PROTEAN",
    // "dscSerialNumber" => "123213131",
    // "dscExpiryDate" => "03/10/2023",
    // "authKey" => "AchariyaUAT"
    //     ];
        $reqEntityData = [
          "entityId" => "AchariyaUAT",
    "dscProvider" => "PROTEAN",
    "dscSerialNumber" => "123213131",
    "dscExpiryDate" => "03/10/2023",
    "authKey" => "AchariyaUAT"
        ];
            // return $this->baseUrl;
        // Make API request
        $reqJson = $this->makeApiRequest('checkEntityBalance', $reqEntityData, 'B');
        
        // Log transaction
        $this->logTransaction('checkEntityBalance', $reqEntityData, $reqJson);
        
        // Return JSON response
        return response()->json([
            'status' => 'success',
            'data' => json_decode($reqJson, true)
        ]);
    }

    /**
     * Get entity reports
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function entityReports(Request $request)
    {
        // Validate request
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date'
        ]);
        
        // Prepare request entity data for entity reports
        $reqEntityData = [
            "entityId" => $request->input('entityId', "AchariyaUAT"),
            "dscProvider" => $request->input('dscProvider', "PROTEAN"),
            "dscSerialNumber" => $request->input('dscSerialNumber', "123213131"),
            "dscExpiryDate" => $request->input('dscExpiryDate', "03/10/2023"),
            "authKey" => $request->input('authKey', "AchariyaUAT"),
            "startDate" => $request->start_date,
            "endDate" => $request->input('end_date', $request->start_date)
        ];

        // Make API request
        $reqJson = $this->makeApiRequest('getEntityReports', $reqEntityData, 'R');
        
        // Log transaction
        $this->logTransaction('getEntityReports', $reqEntityData, $reqJson);
        
        // Return JSON response
        return response()->json([
            'status' => 'success',
            'data' => json_decode($reqJson, true)
        ]);
    }

    /**
     * Process a PAN correction request
     *
     * @param Request $request
     * @return mixed
     */
    public function crPanReq(Request $request)
    {
        // Build form data from request or use defaults for PAN correction
        $formData = [
            "applicantDto" => [
                "appliType" => $request->input('appliType', 'A'),
                "category" => $request->input('category', 'A'),
                "title" => $request->input('title', 0),
                "lastName" => $request->input('lastName', ''),
                "firstName" => $request->input('firstName', ''),
                "middleName" => $request->input('middleName', ''),
                "dob" => $request->input('dob', ''),
                "gender" => $request->input('gender', 'N'),
                "consent" => $request->input('consent', false),
                "nameOnPanCard" => $request->input('nameOnPanCard', ''),
                "pan" => $request->input('pan', ''),
                "applnMode" => $request->input('applnMode', ''),
                "checkboxTitlePath" => $request->input('checkboxTitlePath', ''),
                "checkboxDateOfBirth" => $request->input('checkboxDateOfBirth', ''),
                "checkboxGender" => $request->input('checkboxGender', ''),
                "checkboxPhotoMismatch" => $request->input('checkboxPhotoMismatch', ''),
                "checkboxSignatureMismatch" => $request->input('checkboxSignatureMismatch', ''),
                "tokenNo" => $request->input('tokenNo', ''),
            ],
            "parentsDetails" => [
                "checkboxMothersName" => $request->input('checkboxMothersName', ''),
                "singleParent" => $request->input('singleParent', ''),
                "fatherLastName" => $request->input('fatherLastName', ''),
                "fatherFirstName" => $request->input('fatherFirstName', ''),
                "fatherMiddleName" => $request->input('fatherMiddleName', ''),
                "motherLastName" => $request->input('motherLastName', ''),
                "motherFirstName" => $request->input('motherFirstName', ''),
                "motherMiddleName" => $request->input('motherMiddleName', ''),
                "parentNamePrint" => $request->input('parentNamePrint', 'F'),
                "reEnterFatherLastName" => $request->input('reEnterFatherLastName', ''),
                "reEnterFatherFirstName" => $request->input('reEnterFatherFirstName', ''),
                "reEnterFatherMiddleName" => $request->input('reEnterFatherMiddleName', ''),
                "reEnterMotherLastName" => $request->input('reEnterMotherLastName', ''),
                "reEnterMotherFirstName" => $request->input('reEnterMotherFirstName', ''),
                "reEnterMotherMiddleName" => $request->input('reEnterMotherMiddleName', ''),
            ],
            "otherDetails" => [
                "phyPanIsReq" => $request->input('phyPanIsReq', ''),
                "addrForCommunication" => $request->input('addrForCommunication', ''),
                "officeAddress" => [
                    "flatNo" => $request->input('officeFlatNo', ''),
                    "nameOfPremises" => $request->input('officeNameOfPremises', ''),
                    "road" => $request->input('officeRoad', ''),
                    "area" => $request->input('officeArea', ''),
                    "town" => $request->input('officeTown', ''),
                    "countryName" => $request->input('officeCountryName', "none"),
                    "state" => $request->input('officeState', "none"),
                    "pinCode" => $request->input('officePinCode', ''),
                    "zipCode" => $request->input('officeZipCode', ''),
                    "officeName" => $request->input('officeName', ''),
                ],
                "isdCode" => $request->input('isdCode', ''),
                "stdCode" => $request->input('stdCode', ''),
                "telOrMobNo" => $request->input('telOrMobNo', ''),
                "emailId" => $request->input('emailId', ''),
                "place" => $request->input('place', ''),
                "date" => $request->input('date', ''),
                "residenceAddress" => [
                    "rflatNo" => $request->input('rflatNo', ''),
                    "rnameOfPremises" => $request->input('rnameOfPremises', ''),
                    "rroad" => $request->input('rroad', ''),
                    "rarea" => $request->input('rarea', ''),
                    "rtown" => $request->input('rtown', ''),
                    "rcountryName" => $request->input('rcountryName', "none"),
                    "rstate" => $request->input('rstate', "none"),
                    "rpinCode" => $request->input('rpinCode', ''),
                    "rzipCode" => $request->input('rzipCode', ''),
                ]
            ],
            "checkboxTelephone" => $request->input('checkboxTelephone', ''),
            "checkboxCommnAddress" => $request->input('checkboxCommnAddress', ''),
            "checkboxOtherAdd" => $request->input('checkboxOtherAdd', ''),
            "checkboxResiAddress" => $request->input('checkboxResiAddress', ''),
            "checkboxPanSurr" => $request->input('checkboxPanSurr', ''),
            "pan1" => $request->input('pan1', ''),
            "pan2" => $request->input('pan2', ''),
            "pan3" => $request->input('pan3', ''),
            "pan4" => $request->input('pan4', ''),
        ];

        // Encode form data
        $formDataEncoded = base64_encode(json_encode($formData));

        // Prepare request entity data for PAN correction
        $reqEntityData = [
            "txnid" => $request->input('txnid', $this->generateTransactionId()),
            "reqTs" => now()->subMinutes(5)->format('Y-m-d\TH:i:s'),
            "entityId" => $request->input('entityId', "AchariyaUAT"),
            "branchCode" => $request->input('branchCode', "branchcode"),
            "reqType" => $request->input('reqType', "CR"),
            "dscProvider" => $request->input('dscProvider', "PROTEAN"),
            "dscSerialNumber" => $request->input('dscSerialNumber', "123123123"),
            "dscExpiryDate" => $request->input('dscExpiryDate', "03/10/2023"),
            "returnUrl" => $request->input('returnUrl', "https:\\\\preprod.assisted-service.egov-nsdl.com\\PanApplication\\panRes"),
            "formData" => $formDataEncoded,
            "authKey" => $request->input('authKey', "AchariyaUAT")
        ];

        // Make API request
        $reqJson = $this->makeApiRequest('crPanReq', $reqEntityData, 'C');
        
        // Log transaction
        $this->logTransaction('crPanReq', $reqEntityData, $reqJson);
        
        // Return view for redirection
        return view('pan.redirect', [
            'reqJson' => $reqJson, 
            'url' => $this->baseUrl . '/crPanReq'
        ]);
    }

    /**
     * Make API request to NSDL
     *
     * @param string $endpoint
     * @param array $data
     * @param string $flag
     * @return mixed
     */
    private function makeApiRequest($endpoint, $rawData, $flag)
    {
        // 1. Wrap the request inside appropriate key based on flag
        $reqEntityData = [];
        
        switch ($flag) {
            case 'F':
                $reqEntityData = [
                    "reqEntityData" => $rawData,
                    "signature" => "Signature of reqEntityData"
                ];
                break;
            case 'I':
                $reqEntityData = [
                    "incompleteData" => $rawData,
                    "signature" => "Signature of reqEntityData"
                ];
                break;
            case 'S':
                $reqEntityData = [
                    "panReqEntityData" => $rawData,
                    "signature" => "Signature of data"
                ];
                break;
            case 'T':
                $reqEntityData = [
                    "txnReqEntityData" => $rawData,
                    "signature" => "Signature of txnReqEntityData"
                ];
                break;
            case 'B':
                $reqEntityData = [
                    "billReqDto" => $rawData,
                    "signature" => ""
                ];
                break;
            case 'C':
                $reqEntityData = [
                    "reqEntityData" => $rawData,
                    "signature" => "Signature of reqEntityData"
                ];
                break;
            case 'R':
                $reqEntityData = [
                    "reportsReqDto" => $rawData,
                    "signature" => ""
                ];
                break;
        }

        // 2. Convert to JSON without escaping slashes
        $reqEntityJson = json_encode($reqEntityData, JSON_UNESCAPED_SLASHES);
        
        // 3. Base64 encode the full request entity JSON
        $base64Request = base64_encode($reqEntityJson);

        // 4. Sign the base64 request
        $signature = $this->signRequest($base64Request, $flag);
        
        // 5. Final payload with signature
        $finalPayload = $signature;
        
    //   return $finalPayload;
        // For endpoints that need direct API call instead of redirect
        if ($flag == 'T' || $flag == 'S' || $flag == 'B' || $flag == 'R') {
            // 6. Make API request using cURL
            $apiUrl = $this->baseUrl . '/' . $endpoint;
            $ch = curl_init($apiUrl);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $finalPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($finalPayload),
            ]);
            
            $response = curl_exec($ch);

            // Handle cURL errors
            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                return json_encode(['error' => $error]);
            }

            curl_close($ch);
            return $response;
        }
        
        // Return signature for view-based redirects
        return $signature;
    }

    /**
     * Sign request using Java utility
     *
     * @param string $base64Request
     * @param string $flag
     * @return string
     */
    private function signRequest($base64Request, $flag)
    {
        try {
            // Paths for certificate and signing utility
            $certPath = public_path('YESPAL.pfx');
            $certPassword = env('CERT_PASSWORD', '123456789');
            $jarPath = public_path('Assissted_Signing_v3.jar');
    
            // Get certificate alias
            $alias = $this->getCertificateAlias($certPath, $certPassword);
    
            // Prepare Java command for signing
            $command = "java -jar \"$jarPath\" \"$base64Request\" \"$certPath\" \"$certPassword\" \"$alias\" \"$flag\" 2>&1";
    
            // Execute signing command
            $signedRequest = shell_exec($command);
    
            // Log the command and its output
            Log::info('Signing command executed', ['command' => $command, 'output' => $signedRequest]);
    
            // Extract signature from response
            return $this->extractSignature($signedRequest);
    
        } catch (Exception $e) {
            Log::error('Error signing request', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Optionally throw or return a response
            throw new \RuntimeException('Signing request failed: ' . $e->getMessage());
        }
        
       
    }
    private function extractSignature($logString) {
      $startPos = strpos($logString, '{');
    $endPos = strrpos($logString, '}');

    if ($startPos === false || $endPos === false) {
        return null; // No JSON found
    }

    // Extract the JSON substring
    $jsonString = substr($logString, $startPos, $endPos - $startPos + 1);

    // Decode JSON into array
    // $data = json_decode($jsonString, true);
    // dd($jsonString);
    // Return decoded array or null if decoding fails
    return $jsonString ?: null;
    }
    private function getCertificateAlias($certPath, $certPassword) {
     
        return 'yespal singh'; // Fallback alias
    }
    
    
    public function show()
    {
        return view('uat_logs');
    }
    
 /**
 * Get UAT logs with server-side processing for DataTables
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function uatLogs(Request $request)
{
    $query = DB::table('uatlogs');
    
    // Apply filters
    if ($request->has('endpoint') && !empty($request->endpoint)) {
        $query->where('endpoint', 'like', '%' . $request->endpoint . '%');
    }
    
    if ($request->has('start_date') && !empty($request->start_date)) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }
    
    if ($request->has('end_date') && !empty($request->end_date)) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }
    
    // Get total count before pagination
    $totalRecords = $query->count();
    $filteredRecords = $totalRecords; // If no search is applied
    
    // If search is applied, update filteredRecords
    if ($request->has('search') && !empty($request->input('search.value'))) {
        $searchValue = $request->input('search.value');
        $query->where(function($q) use ($searchValue) {
            $q->where('endpoint', 'like', '%' . $searchValue . '%')
              ->orWhere('id', 'like', '%' . $searchValue . '%')
              ->orWhere('request_data', 'like', '%' . $searchValue . '%')
              ->orWhere('response_data', 'like', '%' . $searchValue . '%');
        });
        
        $filteredRecords = $query->count();
    }
    
    // Apply DataTables server-side processing parameters
    $start = $request->input('start', 0);
    $length = $request->input('length', 10);
    $order = $request->input('order.0.column', 0);
    $dir = $request->input('order.0.dir', 'desc');
    
    // Map column index to column name
    $columns = [
        0 => 'id',
        1 => 'endpoint',
        2 => 'created_at',
        3 => 'updated_at'
    ];
    
    $columnName = $columns[$order] ?? 'id';
    
    // Apply ordering and pagination
    $data = $query->orderBy($columnName, $dir)
                ->offset($start)
                ->limit($length)
                ->get();
    
    return response()->json([
        'draw' => intval($request->input('draw')),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data
    ]);
}

/**
 * Get details for a specific log entry
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getLogDetails(Request $request)
{
    $id = $request->input('id');
    
    $log = DB::table('uatlogs')
        ->where('id', $id)
        ->first();
    
    if (!$log) {
        return response()->json([
            'success' => false,
            'message' => 'Log not found'
        ]);
    }
    
    return response()->json([
        'success' => true,
        'data' => $log
    ]);
}

/**
 * Export logs to CSV file
 *
 * @param Request $request
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
public function exportLogs(Request $request)
{
    // Get date filters if provided
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $endpoint = $request->input('endpoint');
    
    // Base query
    $query = DB::table('uatlogs')
        ->select('id', 'endpoint', 'request_data', 'response_data', 'created_at', 'updated_at');
    
    // Apply filters if provided
    if ($startDate) {
        $query->whereDate('created_at', '>=', $startDate);
    }
    
    if ($endDate) {
        $query->whereDate('created_at', '<=', $endDate);
    }
    
    if ($endpoint) {
        $query->where('endpoint', 'like', '%' . $endpoint . '%');
    }
    
    // Get the results
    $logs = $query->orderBy('id', 'desc')->get();
    
    // Create CSV file
    $filename = 'uatlogs_' . date('Y-m-d_H-i-s') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];
    
    $handle = fopen('php://temp', 'r+');
    
    // Add CSV headers
    fputcsv($handle, ['ID', 'Endpoint', 'Request Data', 'Response Data', 'Created At', 'Updated At']);
    
    // Add log data
    foreach ($logs as $log) {
        fputcsv($handle, [
            $log->id,
            $log->endpoint,
            $log->request_data,
            $log->response_data,
            $log->created_at,
            $log->updated_at
        ]);
    }
    
    rewind($handle);
    $csv = stream_get_contents($handle);
    fclose($handle);
    
    return response($csv, 200, $headers);
}

/**
 * Delete a log entry
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function deleteLog(Request $request)
{
    $id = $request->input('id');
    
    $deleted = DB::table('uatlogs')
        ->where('id', $id)
        ->delete();
    
    if (!$deleted) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete log'
        ]);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Log deleted successfully'
    ]);
}

/**
 * Delete logs older than a specified number of days
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function cleanupLogs(Request $request)
{
    $days = $request->input('days', 30); // Default to 30 days
    
    $cutoffDate = now()->subDays($days)->toDateString();
    
    $deleted = DB::table('uatlogs')  // Changed from 'uat_logs' to 'uatlogs'
        ->whereDate('created_at', '<', $cutoffDate)
        ->delete();
    
    return response()->json([
        'success' => true,
        'message' => $deleted . ' logs older than ' . $days . ' days deleted successfully'
    ]);
}
}