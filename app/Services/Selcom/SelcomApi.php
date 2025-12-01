<?php

namespace App\Services\Selcom;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class SelcomApi
{
    protected $apiKey;
    protected $apiSecret;
    protected $vendorId;
    protected $environment;
    protected $baseUrl;

    public function __construct(array $config)
    {
        // Validate required configuration
        $required = ['api_key', 'api_secret', 'vendor_id'];
        foreach ($required as $key) {
            if (empty($config[$key])) {
                throw new InvalidArgumentException("Missing required configuration: {$key}");
            }
        }

        $this->apiKey = $config['api_key'];
        $this->apiSecret = $config['api_secret'];
        $this->vendorId = $config['vendor_id'];
        $this->environment = $config['environment'] ?? 'sandbox';

        // Use the correct base URL from config
        $this->baseUrl = config('selcom.endpoints.' . $this->environment . '.base_url');
    }

    /**
     * Generate request signature for Selcom API
     */
    protected function generateSignature($data, $timestamp)
    {
        // Start with vendor ID and timestamp
        $payload = $this->vendorId . $timestamp;
        
        // Sort data alphabetically by key for consistent signing
        ksort($data);
        foreach ($data as $key => $value) {
            // Handle nested arrays by converting to JSON
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_SLASHES);
            }
            $payload .= $key . $value;
        }
        
        return hash_hmac('sha256', $payload, $this->apiSecret);
    }

    /**
     * Generate authorization headers with signature
     */
    protected function getAuthorizationHeader($data = [])
    {
        $timestamp = time();
        $signature = $this->generateSignature($data, $timestamp);
        
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Vendor-Id' => $this->vendorId,
            'Timestamp' => $timestamp,
            'Signature' => $signature,
        ];
    }

    /**
     * Make API request with comprehensive error handling
     */
    protected function makeRequest($method, $endpoint, $data = [])
    {
        $url = $this->baseUrl . $endpoint;
        $headers = $this->getAuthorizationHeader($data);

        // Log request (sensitive data masked)
        $loggableData = $this->maskSensitiveData($data);
        Log::info('Selcom API Request', [
            'method' => $method,
            'url' => $url,
            'data' => $loggableData,
            'headers' => array_merge($headers, ['Authorization' => 'Bearer ***'])
        ]);

        try {
            $response = Http::timeout(30)
                ->retry(3, 100)
                ->withHeaders($headers)
                ->$method($url, $data);

            Log::info('Selcom API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Parse error response
            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? $errorBody['error'] ?? $response->body();
            $errorCode = $errorBody['code'] ?? $response->status();
            
            throw new \Exception("Selcom API Error ({$errorCode}): " . $errorMessage);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Selcom API Connection Error', ['error' => $e->getMessage()]);
            throw new \Exception('Selcom API connection failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Selcom API Request Failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Mask sensitive data for logging
     */
    protected function maskSensitiveData(array $data)
    {
        $sensitiveFields = ['card_no', 'cvv', 'pin', 'password', 'api_secret', 'api_key'];
        
        $maskedData = $data;
        foreach ($sensitiveFields as $field) {
            if (isset($maskedData[$field])) {
                $maskedData[$field] = '***';
            }
        }
        
        return $maskedData;
    }

    /**
     * Create order for payment
     */
    public function createOrder(array $data)
    {
        $requiredFields = ['amount', 'currency', 'order_id'];
        $this->validateRequiredFields($data, $requiredFields);

        return $this->makeRequest('post', '/checkout/create-order-minimal', $data);
    }

    /**
     * Create lipa kidogo order
     */
    public function createLipaKidogoOrder(array $data)
    {
        $requiredFields = ['amount', 'currency', 'order_id', 'buyer_phone'];
        $this->validateRequiredFields($data, $requiredFields);

        return $this->makeRequest('post', '/checkout/create-order-lipa-kidogo', $data);
    }

    /**
     * Get order status
     */
    public function getOrderStatus(array $data)
    {
        if (empty($data['order_id']) && empty($data['trans_id'])) {
            throw new InvalidArgumentException('Either order_id or trans_id is required');
        }

        return $this->makeRequest('post', '/checkout/order-status', $data);
    }

    /**
     * Get order status by transaction ID
     */
    public function getOrderStatusByTransId($transId)
    {
        return $this->makeRequest('post', '/checkout/order-status', [
            'trans_id' => $transId
        ]);
    }

    /**
     * Get order status by order ID
     */
    public function getOrderStatusByOrderId($orderId)
    {
        return $this->makeRequest('post', '/checkout/order-status', [
            'order_id' => $orderId
        ]);
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId)
    {
        return $this->makeRequest('post', '/checkout/cancel-order', [
            'order_id' => $orderId
        ]);
    }

    /**
     * Refund transaction
     */
    public function refundTransaction($transId, $amount = null)
    {
        $data = ['trans_id' => $transId];
        if ($amount) {
            $data['amount'] = $amount;
        }
        
        return $this->makeRequest('post', '/checkout/refund', $data);
    }

    /**
     * Validate webhook signature
     */
    public function validateWebhookSignature($payload, $signature, $timestamp)
    {
        $expectedSignature = $this->generateSignature($payload, $timestamp);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Validate required fields in request data
     */
    protected function validateRequiredFields(array $data, array $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("Missing required field: {$field}");
            }
        }
    }

    /**
     * Get API configuration (for debugging purposes)
     */
    public function getConfig()
    {
        return [
            'vendor_id' => $this->vendorId,
            'environment' => $this->environment,
            'base_url' => $this->baseUrl
        ];
    }

    /**
     * Test API connectivity
     */
    public function testConnection()
    {
        try {
            // Use a simple endpoint to test connectivity
            $response = $this->getOrderStatusByOrderId('test_' . time());
            // If we get any response (even error), connection is working
            return true;
        } catch (\Exception $e) {
            // If it's a connection error, return false
            if (str_contains($e->getMessage(), 'connection')) {
                return false;
            }
            // Other errors mean connection is working but request was invalid
            return true;
        }
    }
}