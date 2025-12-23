<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SelcomService
{
    protected $apiKey;
    protected $apiSecret;
    protected $vendorId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('selcom.api_key', env('SELCOM_API_KEY'));
        $this->apiSecret = config('selcom.api_secret', env('SELCOM_API_SECRET'));
        $this->vendorId = config('selcom.vendor_id', env('SELCOM_VENDOR_ID'));
        $this->baseUrl = config('selcom.base_url', env('SELCOM_BASE_URL', 'https://api.selcom.co.tz'));
    }

    /**
     * Generate authorization header for Selcom API
     */
    protected function generateAuthHeader()
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $data = $this->apiKey . $this->apiSecret . $timestamp;
        $signature = hash('sha256', $data);

        return [
            'Authorization' => 'SELCOM ' . base64_encode($this->apiKey . ':' . $signature),
            'Digest-Method' => 'HS256',
            'Timestamp' => $timestamp,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Initiate a standard payment
     */
    public function initiatePayment(
        $orderId,
        $amount,
        $phone,
        $description,
        $callbackUrl = null,
        $email = null,
        $name = null,
        $successUrl = null,
        $failUrl = null
    ) {
        try {
            $payload = [
                'vendor' => $this->vendorId,
                'order_id' => $orderId,
                'buyer_email' => $email ?: 'noreply@example.com',
                'buyer_name' => $name ?: 'Customer',
                'buyer_phone' => $phone,
                'amount' => $amount,
                'currency' => 'TZS',
                'buyer_remarks' => $description,
                'merchant_remarks' => $description,
                'no_of_items' => 1,
            ];

            if ($callbackUrl) {
                $payload['callback_url'] = $callbackUrl;
            }

            if ($successUrl) {
                $payload['redirect_url_success'] = $successUrl;
            }

            if ($failUrl) {
                $payload['redirect_url_failed'] = $failUrl;
            }

            $headers = $this->generateAuthHeader();

            $response = Http::withHeaders($headers)
                ->post($this->baseUrl . '/v1/checkout/create-order', $payload);

            $result = $response->json();

            Log::info('Selcom payment initiation', [
                'order_id' => $orderId,
                'amount' => $amount,
                'response' => $result
            ]);

            if ($response->successful() && isset($result['payment_status']) && $result['payment_status'] === 'PENDING') {
                return $result;
            }

            throw new \Exception('Payment initiation failed: ' . ($result['message'] ?? 'Unknown error'));

        } catch (\Exception $e) {
            Log::error('Selcom payment initiation error', [
                'order_id' => $orderId,
