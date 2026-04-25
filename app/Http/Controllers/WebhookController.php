<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function paystack(Request $request)
    {
        // 1. Validate Secret Key
        if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !array_key_exists('HTTP_X_PAYSTACK_SIGNATURE', $_SERVER)) {
            // only a post with paystack signature header gets our attention
            return response()->json(['message' => 'Invalid header'], 400);
        }

        // 2. Read the signature from the header
        $signature = $request->header('x-paystack-signature');

        // 3. Confirm that the signature is right
        $secret = config('services.paystack.secret_key');
        if (!$secret) {
            return response()->json(['message' => 'Paystack secret not configured'], 500);
        }

        if ($signature !== hash_hmac('sha512', $request->getContent(), $secret)) {
            // Invalid signature
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // 4. Securely process payload
        $payload = json_decode($request->getContent(), true);

        // We only care about charge.success
        if ($payload['event'] === 'charge.success') {
            $reference = $payload['data']['reference'];

            // Find the order that was created with this transaction reference
            $order = \App\Models\Order::where('transaction_reference', $reference)->first();

            if ($order) {
                // Update voice of truth
                $order->update(['status' => 'processing']);

                // Send email receipt
                try {
                    \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderReceipt($order));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send receipt email: ' . $e->getMessage());
                }
            }
        }

        // Paystack expects a 200 OK so it stops retrying the webhook
        return response()->json(['status' => 'success'], 200);
    }
}
