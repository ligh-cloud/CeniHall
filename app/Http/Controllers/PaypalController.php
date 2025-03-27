<?php

namespace App\Http\Controllers;

use App\Services\PaiementService;
use Illuminate\Http\Request;

class PaypalController extends Controller
{
    protected $paymentService;

    public function __construct(PaiementService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        return response()->json($this->paymentService->getAllPayments());
    }

    public function show($id)
    {
        $payment = $this->paymentService->getPaymentById($id);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return response()->json($payment);
    }

    public function createPayment(Request $request)
    {
        return $this->paymentService->createPayment([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'status' => 'pending',

            'reservation_id' => $request->reservation_id,
        ]);
    }

    public function capturePayment(Request $request)
    {
        $payment = $this->paymentService->capturePayPalOrder($request->orderID);

        if (isset($payment['status']) && $payment['status'] === 'COMPLETED') {
            $reservation = \App\Models\Reservation::find($request->reservation_id);

            if ($reservation) {
                $reservation->status = true;
                $reservation->save();
            }

            $this->paymentService->createPayment([
                'user_id' => auth()->id(),
                'amount' => $request->amount,
                'status' => 'completed',
                'transaction_id' => $payment['id'],
                'reservation_id' => $request->reservation_id,
            ]);

            return response()->json(['message' => 'Payment successful, reservation confirmed!', 'payment' => $payment]);
        }

        return response()->json(['error' => 'Payment failed'], 500);
    }

    public function destroy($id)
    {
        $deleted = $this->paymentService->deletePayment($id);
        if (!$deleted) {
            return response()->json(['message' => 'Payment not found'], 404);
        }
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
