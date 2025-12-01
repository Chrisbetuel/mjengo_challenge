<?php

namespace App\Services;

use App\Services\Selcom\SelcomApi;
use Illuminate\Support\Facades\Log;

class SelcomService
{
    protected $selcomApi;
    protected $apiKey;
    protected $apiSecret;
    protected $vendorId;

    public function __construct()
    {
        $this->apiKey = config('selcom.api_key');
        $this->apiSecret = config('selcom.api_secret');
        $this->vendorId = config('selcom.vendor_id');

        $this->selcomApi = new SelcomApi([
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'vendor_id' => $this->vendorId,
            'environment' => config('selcom.environment', 'sandbox')
        ]);
    }



    /**
     * Initiate a payment request
     */
    public function initiatePayment($orderId, $amount, $phone, $description = '', $callbackUrl = null, $buyerEmail = null, $buyerName = null, $redirectUrl = null, $cancelUrl = null)
    {
        try {
            $currency = config('selcom.currency', 'TZS');

            $paymentData = [
                'vendor' => $this->vendorId,
                'order_id' => $orderId,
                'buyer_email' => $buyerEmail,
                'buyer_phone' => $phone,
                'amount' => $amount,
                'currency' => $currency,
                'redirect_url' => $redirectUrl ?: route('challenges.index'),
                'cancel_url' => $cancelUrl ?: route('challenges.index'),
                'buyer_name' => $buyerName
            ];

            $response = $this->selcomApi->createOrder($paymentData);

            Log::info('Selcom payment initiated', [
                'order_id' => $orderId,
                'amount' => $amount,
                'response' => $response
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Selcom payment initiation failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($orderId, $transId = null)
    {
        try {
            $statusData = [
                'order_id' => $orderId
            ];

            if ($transId) {
                $statusData['trans_id'] = $transId;
            }

            $response = $this->selcomApi->getOrderStatus($statusData);

            Log::info('Selcom payment status checked', [
                'order_id' => $orderId,
                'trans_id' => $transId,
                'response' => $response
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Selcom payment status check failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Process lipa kidogo (installment payment)
     */
    public function initiateLipaKidogo($orderId, $totalAmount, $installmentAmount, $numInstallments, $phone, $buyerEmail = null, $buyerName = null, $redirectUrl = null, $cancelUrl = null)
    {
        try {
            $currency = config('selcom.currency', 'TZS');

            $lipaKidogoData = [
                'vendor' => $this->vendorId,
                'order_id' => $orderId,
                'buyer_email' => $buyerEmail,
                'buyer_phone' => $phone,
                'amount' => (float) $totalAmount,
                'currency' => $currency,
                'redirect_url' => $redirectUrl ?: route('challenges.index'),
                'cancel_url' => $cancelUrl ?: route('challenges.index'),
                'buyer_name' => $buyerName
            ];

            $response = $this->selcomApi->createLipaKidogoOrder($lipaKidogoData);

            Log::info('Selcom lipa kidogo initiated', [
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'installments' => $numInstallments,
                'response' => $response
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Selcom lipa kidogo initiation failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Process direct payment
     */
    public function initiateDirectPayment($orderId, $amount, $phone)
    {
        return $this->initiatePayment($orderId, $amount, $phone, 'Direct Challenge Payment');
    }

    /**
     * Validate callback data
     */
    public function validateCallback($callbackData)
    {
        // Basic validation
        if (!isset($callbackData['order_id']) || !isset($callbackData['status'])) {
            return false;
        }

        // Verify signature if present
        if (isset($callbackData['signature']) &&
            isset($callbackData['order_id']) &&
            isset($callbackData['amount']) &&
            isset($callbackData['currency']) &&
            isset($callbackData['timestamp'])) {

            // Use SelcomApi's signature generation for validation
            $expectedSignature = $this->selcomApi->generateSignature($callbackData, $callbackData['timestamp']);

            if (!hash_equals($expectedSignature, $callbackData['signature'])) {
                Log::warning('Selcom callback signature validation failed', [
                    'order_id' => $callbackData['order_id'],
                    'expected_signature' => $expectedSignature,
                    'received_signature' => $callbackData['signature']
                ]);
                return false;
            }
        }

        return true;
    }
}
