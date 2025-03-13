<?php

namespace App\Http\Controllers\API;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Helpers\Helper;
use Nette\Utils\Random;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use App\Models\UserDonation;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\DonationPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\SignatureVerificationException;

class DonationWithStripeController extends Controller
{

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function donate(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'amount' => 'required|numeric|min:1',
        ]);

        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Donation',
                    ],
                    'unit_amount' => $request->amount * 100, // Convert to cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'https://maoi-react-frontend.netlify.app/donation-success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://maoi-react-frontend.netlify.app/donation-failed',
            'metadata' => [
                'amount' => $request->amount,
                'email' => $request->email ?? null,
            ],
        ]);

        $transactionId = $checkout_session->id;

        DonationPayment::create([
            'email' => $request->email,
            'amount' => $request->amount,
            'transaction_id' => $transactionId,
            'status' => 'pending',
        ]);

        UserDonation::create([
            'user_id' => auth()->user()->id ?? null,
            'email' => $request->email ?? null,
            'donation_amount' => $request->amount,
            'currency' => 'USD',
            'transaction_id' => $transactionId,
            'status' => 'pending',
        ]);
        Log::info('Working Create Intent');

        return response()->json(['checkout_url' => $checkout_session->url]);
    }

    public function handleWebhook(Request $request)
    {
        Log::info('Working');
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

    private function convertToRealAmount($amount, $conversionFactor = 100)
    {
        if ($amount > 0) {
            $realAmount = $amount / $conversionFactor;
            return round($realAmount, 3);
        } else {
            return 0.00;
        }
    }
}
