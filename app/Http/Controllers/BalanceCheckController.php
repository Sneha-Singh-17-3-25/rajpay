<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;

class BalanceCheckController extends Controller {
    /**
     * Check Entity Balance using DSC Signing
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
       return view("service.account.balance_check");
    }
     
    public function checkBalance(Request $request) {
        try {
            // Generate unique transaction ID
            $txnid = 'TXN' . time();

            // Prepare the request payload according to API prototype
            $requestData = [
                "entityId" => $request->input('entityId', 'NSDL1234'),
                "dscProvider" => $request->input('dscProvider', 'NSDL'),
                "dscSerialNumber" => $request->input('dscSerialNumber', '123213131'),
                "dscExpiryDate" => $request->input('dscExpiryDate', ''),
                "authKey" => $request->input('authKey', 'Entity_ID')
            ];

            // Convert request to Base64
            $base64Request = base64_encode(json_encode($requestData));

            // Paths for certificate and signing utility
            $certPath = public_path('YESPAL.pfx');
            $certPassword = env('CERT_PASSWORD', '123456789');
            $jarPath = public_path('Assissted_Signing_v3.jar');

            // Get certificate alias
            $alias = $this->getCertificateAlias($certPath, $certPassword);
            $flag = 'B'; // Billing flag

            // Prepare Java command for signing
            $command = "java -jar \"$jarPath\" \"$base64Request\" \"$certPath\" \"$certPassword\" \"$alias\" \"$flag\" 2>&1";

            // Execute signing command
            $signedRequest = shell_exec($command);

            // Extract signature from response
            $signature = $this->extractSignature($signedRequest);

            // Log the transaction
            $this->logBalanceCheckRequest($txnid, $requestData, $signedRequest);

            // Prepare API request
            $apiPayload = [
                'billReqDto' => $requestData,
                'signature' => $signature
            ];  
            // dd($signature);

            // Send request to Protean API
            $response = $this->sendBalanceCheckRequest($apiPayload);

            return response()->json($response);

        } catch (Exception $e) {
            // Log and handle exceptions
            Log::error('Balance Check Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during balance check',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extract signature from signed request
     *
     * @param string $signedRequest
     * @return string|null
     */
    private function extractSignature($signedRequest) {
        if (preg_match('/\{.*\}/', $signedRequest, $matches)) {
            $data = json_decode($matches[0], true);
            return $data['signature'] ?? null;
        }
        return null;
    }

    /**
     * Log balance check request details
     *
     * @param string $txnid
     * @param array $requestData
     * @param string $signedRequest
     */
    private function logBalanceCheckRequest($txnid, $requestData, $signedRequest) {
        DB::table('balance_checks')->insert([
            'txnid' => $txnid,
            'entity_id' => $requestData['entityId'],
            'dsc_provider' => $requestData['dscProvider'],
            'dsc_serial_number' => $requestData['dscSerialNumber'],
            'dsc_expiry_date' => $requestData['dscExpiryDate'],
            'request_data' => json_encode($requestData),
            'signed_request' => $signedRequest,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Send balance check request to Protean API
     *
     * @param array $apiPayload
     * @return array
     */
    private function sendBalanceCheckRequest($apiPayload) {
        try {
            $client = new Client();
            $apiUrl = config('services.protean.balance_check_url', 'https://preprod.assisted-service.egov-nsdl.com/SpringBootFormHandling/checkEntityBalance');

            $response = $client->post($apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $apiPayload
            ]);

            $responseBody = json_decode((string) $response->getBody(), true);

            // Update transaction status
            DB::table('balance_checks')
                ->where('txnid', $apiPayload['billReqDto']['entityId'])
                ->update([
                    'response_data' => json_encode($responseBody),
                    'status' => 'success',
                    'updated_at' => now()
                ]);

            return [
                'status' => 'success',
                'data' => $responseBody
            ];

        } catch (RequestException $e) {
            Log::error('Protean API Error: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => 'Failed to fetch balance',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get certificate alias from P12 keystore
     *
     * @param string $certPath
     * @param string $certPassword
     * @return string|null
     */
    private function getCertificateAlias($certPath, $certPassword) {
        $cmd = "keytool -v -list -storetype pkcs12 -keystore $certPath";
        $output = shell_exec("echo $certPassword | $cmd");
        
        if (preg_match('/Alias name: (.+)/', $output, $matches)) {
            return trim($matches[1]);
        }
        
        return 'yespal singh'; // Fallback alias
    }
}