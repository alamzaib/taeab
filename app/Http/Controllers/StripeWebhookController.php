<?php

namespace App\Http\Controllers;

use App\Models\PaymentLink;
use App\Models\ApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'checkout.session.async_payment_succeeded':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'checkout.session.async_payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;

            default:
                Log::info('Unhandled Stripe webhook event: ' . $event->type);
        }

        return response()->json(['received' => true]);
    }

    protected function handleCheckoutSessionCompleted($session)
    {
        $paymentLink = PaymentLink::where('stripe_checkout_session_id', $session->id)->first();

        if ($paymentLink && $paymentLink->status === 'pending') {
            $paymentLink->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Create success notification
            $this->createPaymentNotification($paymentLink, 'success');
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Find payment link by session ID from metadata
        $sessionId = $paymentIntent->metadata->checkout_session_id ?? null;
        if ($sessionId) {
            $paymentLink = PaymentLink::where('stripe_checkout_session_id', $sessionId)->first();
            
            if ($paymentLink && $paymentLink->status === 'pending') {
                $paymentLink->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                $this->createPaymentNotification($paymentLink, 'success');
            }
        }
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $sessionId = $paymentIntent->metadata->checkout_session_id ?? null;
        if ($sessionId) {
            $paymentLink = PaymentLink::where('stripe_checkout_session_id', $sessionId)->first();
            
            if ($paymentLink && $paymentLink->status === 'pending') {
                $paymentLink->update([
                    'status' => 'failed',
                ]);

                $this->createPaymentNotification($paymentLink, 'failed');
            }
        }
    }

    protected function createPaymentNotification(PaymentLink $paymentLink, string $status)
    {
        $statusText = $status === 'success' ? 'Payment Successful' : 'Payment Failed';
        $message = $status === 'success'
            ? "Your payment of {$paymentLink->currency} {$paymentLink->amount} has been successfully processed."
            : "Your payment of {$paymentLink->currency} {$paymentLink->amount} has failed. Please try again.";

        ApplicationNotification::create([
            'recipient_type' => $paymentLink->recipient_type,
            'recipient_id' => $paymentLink->recipient_id,
            'type' => 'payment',
            'title' => $statusText,
            'message' => $message,
            'email_sent' => false,
        ]);
    }
}
