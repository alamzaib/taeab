<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentLink;
use App\Models\Seeker;
use App\Models\Company;
use App\Models\ApplicationNotification;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class PaymentLinkController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        $paymentLinks = PaymentLink::with('recipient')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payment-links.index', compact('paymentLinks'));
    }

    public function create()
    {
        $seekers = Seeker::orderBy('name')->get(['id', 'name', 'email']);
        $companies = Company::orderBy('company_name')->get(['id', 'company_name', 'email']);

        return view('admin.payment-links.create', compact('seekers', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => 'required|in:seeker,company',
            'recipient_id' => 'required|integer',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|size:3',
            'description' => 'nullable|string|max:500',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);

        try {
            $recipient = $validated['recipient_type'] === 'seeker' 
                ? Seeker::findOrFail($validated['recipient_id'])
                : Company::findOrFail($validated['recipient_id']);

            $amount = $validated['amount'] * 100; // Convert to cents
            $currency = $validated['currency'] ?? 'USD';
            $expiresInDays = isset($validated['expires_in_days']) && $validated['expires_in_days'] 
                ? (int) $validated['expires_in_days'] 
                : 30;
            $expiresAt = now()->addDays($expiresInDays);

            // Create Stripe Checkout Session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => $validated['description'] ?? 'Payment',
                        ],
                        'unit_amount' => (int) $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/payment/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/payment/cancel'),
                'expires_at' => $expiresAt->timestamp,
                'metadata' => [
                    'recipient_type' => $validated['recipient_type'],
                    'recipient_id' => (string) $validated['recipient_id'],
                    'payment_link_id' => null, // Will be updated after creation
                ],
            ]);

            // Create payment link record
            $paymentLink = PaymentLink::create([
                'recipient_type' => $validated['recipient_type'],
                'recipient_id' => $validated['recipient_id'],
                'stripe_checkout_session_id' => $session->id,
                'amount' => $validated['amount'],
                'currency' => $currency,
                'description' => $validated['description'],
                'status' => 'pending',
                'payment_url' => $session->url,
                'expires_at' => $expiresAt,
                'metadata' => [
                    'payment_link_id' => null, // Will be set after save
                    'session_id' => $session->id,
                ],
            ]);

            // Update session metadata with payment link ID
            try {
                Session::update($session->id, [
                    'metadata' => [
                        'recipient_type' => $validated['recipient_type'],
                        'recipient_id' => (string) $validated['recipient_id'],
                        'payment_link_id' => (string) $paymentLink->id,
                    ],
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to update session metadata: ' . $e->getMessage());
            }

            // Create notification for payment link generation
            $recipientName = $validated['recipient_type'] === 'seeker' 
                ? $recipient->name 
                : $recipient->company_name;
            $recipientEmail = $recipient->email;

            ApplicationNotification::create([
                'recipient_type' => $validated['recipient_type'],
                'recipient_id' => $validated['recipient_id'],
                'type' => 'payment_link',
                'title' => 'Payment Link Generated',
                'message' => "A payment link has been generated for you. Amount: {$currency} {$validated['amount']}. Please complete the payment using the link provided.",
                'email_sent' => false,
            ]);

            return redirect()->route('admin.payment-links.index')
                ->with('success', 'Payment link generated successfully!');
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create payment link: ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Payment Link Creation Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(PaymentLink $paymentLink)
    {
        $paymentLink->load('recipient');
        return view('admin.payment-links.show', compact('paymentLink'));
    }

    public function getRecipients(Request $request)
    {
        $type = $request->get('type');
        
        if ($type === 'seeker') {
            $recipients = Seeker::orderBy('name')->get(['id', 'name', 'email'])->map(function($seeker) {
                return [
                    'id' => $seeker->id,
                    'name' => $seeker->name,
                    'email' => $seeker->email,
                ];
            });
        } elseif ($type === 'company') {
            $recipients = Company::orderBy('company_name')->get(['id', 'company_name', 'email'])->map(function($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->company_name,
                    'email' => $company->email,
                ];
            });
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        
        return response()->json($recipients);
    }

    public function generateInvoice(PaymentLink $paymentLink)
    {
        if ($paymentLink->status !== 'paid') {
            return back()->with('error', 'Invoice can only be generated for paid payments.');
        }

        try {
            // Generate invoice using Stripe
            if (!$paymentLink->stripe_invoice_id) {
                // Create invoice if not exists
                $invoice = \Stripe\Invoice::create([
                    'customer' => null, // We'll use metadata
                    'collection_method' => 'send_invoice',
                    'metadata' => [
                        'payment_link_id' => $paymentLink->id,
                        'recipient_type' => $paymentLink->recipient_type,
                        'recipient_id' => $paymentLink->recipient_id,
                    ],
                ]);

                $paymentLink->update([
                    'stripe_invoice_id' => $invoice->id,
                ]);
            } else {
                $invoice = \Stripe\Invoice::retrieve($paymentLink->stripe_invoice_id);
            }

            return view('admin.payment-links.invoice', compact('paymentLink', 'invoice'));
        } catch (\Exception $e) {
            Log::error('Invoice Generation Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }
}
