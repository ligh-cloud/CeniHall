<?php

namespace App\Services;

use App\Repositories\PaymentRepositoryInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaiementService
{
    protected $paymentRepository;
    protected $paypal;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->paypal = new PayPalClient();
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->getAccessToken();
    }

    public function createPayPalOrder($amount)
    {
        $order = $this->paypal->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $amount
                    ]
                ]
            ]
        ]);

        return $order;
    }

    public function capturePayPalOrder($orderId)
    {
        return $this->paypal->capturePaymentOrder($orderId);
    }

    public function createPayment(array $data)
    {
        return $this->paymentRepository->create($data);
    }

    public function getAllPayments()
    {
        return $this->paymentRepository->all();
    }

    public function getPaymentById($id)
    {
        return $this->paymentRepository->find($id);
    }

    public function deletePayment($id)
    {
        $payment = $this->paymentRepository->find($id);
        if ($payment) {
            return $this->paymentRepository->delete($payment);
        }
        return false;
    }
}
