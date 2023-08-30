<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function stripe()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.test_secret'));
        $endpoint_secret = config('services.stripe.webhook_secret');
        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::info('Webhook error while parsing basic request.');
            abort(500);
            exit();
        }

        if ($endpoint_secret) {
            // Only verify the event if there is an endpoint secret defined
            // Otherwise use the basic decoded event
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                Log::info('Webhook error while validating signature.');
                abort(500);
                exit();
            }
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $checkoutSession = $event->data->object; // contains a \Stripe\PaymentIntent
                $author_uuid = $checkoutSession->client_reference_id;
                $listing = Listing::where('author_uuid', $author_uuid)->latest()->first();
                $listing->expires_at = Carbon::now()->addMonth();
                $listing->save();

                Log::info($listing);
                break;

            default:
                // Unexpected event type
                Log::info('Received unknown event type');
        }

        http_response_code(200);
    }
}
