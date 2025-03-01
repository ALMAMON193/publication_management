<?php

namespace App\Http\Controllers\API;

use App\Models\DonationPayment;
use Exception;
use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\UserDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class DonationPaymentController extends Controller
{

    public function DonationPayment(Request $request)
    {
        // Validate membership ID
        $request->validate([
            'donation_amount' => 'required|numeric',
            'email' => 'nullable|email',
        ]);

        // Get authenticated user
        $user = auth()->user();
        // Create a pending payment record
        $transactionId = substr(uniqid('txn_', true), 0, 16);
        $payment = DonationPayment::create([
            'user_id' => $user ? $user->id : null,
            'email' => $request->email ?? ($user ? $user->email : null),
            'amount' => $request->donation_amount,
            'currency' => 'USD',
            'transaction_id' => $transactionId,
            'status' => 'pending',
        ]);
        if (!$payment) {
            return Helper::jsonErrorResponse('Payment failed', 400);
        }
        // Create user Donation record
        UserDonation::create([
            'user_id' => $payment->user_id ?? null,
            'email' => $payment->email ?? null,
            'donation_amount' => $payment->amount,
            'currency' => 'USD',
            'status' => 'successful',
        ]);

        // Initialize PayPal client
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Create PayPal order
        $paymentResponse = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $request->donation_amount,
                    'transaction_id' => $transactionId,
                    'description' => 'Donation Payment for User: ' . ($user ? $user->name : $request->email) . ' (' . ($user ? $user->email : $request->email) . ')' . ' - ' . $request->donation_amount . ' USD',
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('donation.payment.cancel'),
                "return_url" => route('donation.payment.success', ['payment_id' => $payment->id]),
            ]
        ]);

        // Redirect to PayPal approval URL if order is created successfully
        if (isset($paymentResponse['id'])) {
            foreach ($paymentResponse['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return response()->json(['link' => $link['href']], 200);
                }
            }
        }

        return Helper::jsonErrorResponse('Payment failed', 400);
    }

    public function donationSuccess(Request $request)
    {
        // Retrieve payment record
        $payment = DonationPayment::find($request->payment_id);
        if (!$payment) return response()->json(['message' => 'Payment order not found'], 404);
        if ($payment->status !== 'pending') return response()->json(['message' => 'Invalid payment order status'], 400);

        // Retrieve user donation record
        $userDonation = UserDonation::where('user_id', $payment->user_id)->first();
        if (!$userDonation) return response()->json(['message' => 'User donation record not found'], 404);

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
                //update user donation record
                $userDonation->update(['status' => 'successful']);
                DB::commit();
                return redirect('https://maoi-react-frontend.netlify.app/donation-success')->with([
                    'status' => true,
                    'message' => 'Payment successful',
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

    public function donationsCancel()
    {
        return redirect('https://maoi-react-frontend.netlify.app/donation-failed')->with([
            'status' => false,
            'message' => 'Payment was cancelled',
        ], 200);
    }
}
