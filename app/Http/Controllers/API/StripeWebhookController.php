<?php

namespace App\Http\Controllers\API;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\UserDonation;
use Illuminate\Http\Request;
use App\Models\DonationPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_WEBHOOK_SECRET'));
    }
    public function handleWebhook(Request $request)
    {
        Log::info('stripe webhook: ' . $request->getContent());
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook Invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook Invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 500);
        }

        Log::info('Stripe webhook: Received event type: ' . $event->type);

        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $amount = $this->convertToRealAmount($paymentIntent->amount);
                $email = $paymentIntent->customer_details->email;
                $transaction_id = $paymentIntent->id;

                DonationPayment::where('transaction_id', $transaction_id)->update(['status' => 'successful']);
                UserDonation::where('transaction_id', $transaction_id)->update(['status' => 'successful']);
                Log::info('Stripe webhook: Update DonationPayment and UserDonation with transaction_id: ' . $transaction_id);
                break;

            default:
                Log::info('Stripe webhook: Received unknown event type: ' . $event->type);
                return response()->json(['error' => 'Received unknown event type: ' . $event->type], 400);
        }
    }

    public function convertToRealAmount($amount, $conversionFactor = 100)
    {
        if ($amount > 0) {
            $realAmount = $amount / $conversionFactor;
            return round($realAmount, 3);
        } else {
            return 0.00;
        }
    }
}
