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
        $amount = $request->amount;
        $order = $this->paymentService->createPayPalOrder($amount);

        if ($order && isset($order['id'])) {
            // You may need to store the order ID in your database or return it to the user for the capture process
            return response()->json([
                'message' => 'Order created successfully!',
                'order_id' => $order['id'],
            ]);
        }

        return response()->json(['error' => 'Failed to create PayPal order'], 500);
    }

    public function capturePayment(Request $request)
    {


        // Call the payment service to capture the PayPal order
        $payment = $this->paymentService->capturePayPalOrder($request->orderID);

        // Debugging: Check the response from PayPal


        // Check if the payment was successful
        if (isset($payment['status']) && $payment['status'] === 'COMPLETED') {
            // Find the reservation based on the reservation_id in the request
            $reservation = \App\Models\Reservation::find($request->reservation_id);

            // If reservation exists, update the status
            if ($reservation) {
                $reservation->status = true;  // Update to true (boolean)
                $reservation->save();
            }

            // Create a payment record in the database
            $this->paymentService->createPayment([
                'user_id' => auth()->id(),
                'amount' => $request->amount,
                'status' => 'completed',  // The payment status is 'completed'
                'transaction_id' => $payment['id'],  // PayPal transaction ID
                'reservation_id' => $request->reservation_id,  // Link to reservation
            ]);

            // Return success response
            return response()->json(['message' => 'Payment successful, reservation confirmed!', 'payment' => $payment]);
        }

        // If payment was not successful
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
