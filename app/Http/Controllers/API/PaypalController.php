<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Models\UserMembership;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{

    public function makePayment(Request $request)
    {

        // Validate membership ID
        $request->validate([
            'membership_id' => 'required|exists:memberships,id',
        ]);
        // Get authenticated user
        $user = auth()->user();
        if (!$user) return Helper::jsonErrorResponse('User not found', 404);

        // Fetch membership details
        $membership = (new Membership)->find($request->membership_id);
        if (!$membership) return Helper::jsonErrorResponse('Membership not found', 404);

        // Check if user already has an active membership
        if (UserMembership::where('user_id', $user->id)->where('membership_id', $membership->id)->where('status', 'active')->exists()) {
            return Helper::jsonErrorResponse('User already has this membership', 400);
        }
        // Create a pending payment record
        $transactionId = substr(uniqid('txn_', true), 0, 16);
        try {


            $payment = Payment::create([
                'user_id' => $user->id,
                'membership_id' => $membership->id,
                'amount' => $membership->price,
                'currency' => 'USD',
                'transaction_id' => $transactionId,
                'status' => 'pending',
            ]);

            // Initialize PayPal client
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $paymentResponse = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $membership->price,
                        ],
                        "custom_id" => $payment->id,
                        "description" => "Membership Purchase: {$membership->name}",
                        "invoice_id" => $transactionId,
                    ],
                ],
                'application_context' => [
                    'return_url' => route("payment.success", ['payment_id' => $payment->id]),
                    'cancel_url' => route("payment.cancel"),
                ]
            ]);
            // dd($paymentResponse);
            Log::info($paymentResponse);
            // Redirect to PayPal approval URL if order is created successfully
            if (isset($paymentResponse['id'])) {
                foreach ($paymentResponse['links'] as $link) {
                    if ($link['rel'] == 'approve') {
                        return response()->json([
                            'status' => 'true',
                            'payment_id' => $payment->id,
                            'membership_id' => $membership->id,
                            'link' => $link['href'],
                            'message' => 'Payment initialization successful',
                        ], 200);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Payment failed: ' . $e->getMessage());
            return Helper::jsonErrorResponse('Payment failed', 400);
        }
    }

    public function success(Request $request)
    {
        // Retrieve payment record
        $payment = Payment::find($request->payment_id);
        if (!$payment) return response()->json(['message' => 'Payment order not found'], 404);
        Log::info($payment);
        if ($payment->status !== 'pending') return response()->json(['message' => 'Invalid payment order status'], 400);
        // Initialize PayPal client
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Capture PayPal payment
        $paymentResponse = $provider->capturePaymentOrder($request->token);

        if (isset($paymentResponse['status']) && $paymentResponse['status'] === 'COMPLETED') {
            DB::beginTransaction();
            try {
                // Update payment status to successful
                $payment->update(['status' => 'successful']);

                // Create user membership record
                $membership = Membership::where('id', $payment->membership_id)->first();
                if (!$membership) return response()->json(['message' => 'Membership not found'], 404);

                UserMembership::create([
                    'user_id' => $payment->user_id,
                    'membership_id' => $payment->membership_id,
                    'start_date' => now(),
                    'end_date' => now()->addDays($membership->duration),
                    'status' => 'active',
                ]);

                DB::commit();
                return redirect('https://maoi-react-frontend.netlify.app/payment-success')->with([
                    'status' => true,
                    'message' => 'Payment successful',
                    'payment' => $payment,
                ], 200);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
            }
        }

        // Update payment status to failed if PayPal capture fails
        $payment->update(['status' => 'failed']);
        return response()->json(['message' => 'Payment failed'], 400);
    }

    public function cancel()
    {
        return redirect('https://maoi-react-frontend.netlify.app/payment-failed')->with([
            'status' => false,
            'message' => 'Payment Canceled',
        ], 200);
    }
}
