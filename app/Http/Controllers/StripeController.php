<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\StripeCard;
use App\Models\StripeProduct;
use App\Models\StripeSubscription;
use App\Classes\Helper\StripeConnect;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{

    public function stripeWebhook()
    {

        // This is a public sample test API key.
        // Don’t submit any personally identifiable information in requests made with this key.
        // Sign in to see your own test API key embedded in code samples.
        \Stripe\Stripe::setApiKey(env('STRIPE_API_KEY'));
        // Replace this endpoint secret with your endpoint's unique secret
        // If you are testing with the CLI, find the secret by running 'stripe listen'
        // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
        // at https://dashboard.stripe.com/webhooks
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $event = null;

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
            // Log::error('stripe webhook event--' . $event);
        } catch (\UnexpectedValueException$e) {
            // Invalid payload
            Log::error('Invalid payload -- stripe webhook error--' . $e->getMessage());
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException$e) {
            // Invalid signature
            Log::error('Invalid signature -- stripe webhook error--' . $e->getMessage());
            http_response_code(400);
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
            } catch (\Stripe\Exception\SignatureVerificationException$e) {
                // Invalid signature
                Log::error('Invalid signature -- stripe webhook error--' . $e->getMessage());
                echo 'Webhook error while validating signature.';
                http_response_code(400);
                exit();
            }
        }

        // Handle the event
        switch ($event->type) {
            //Whenever any subscription update we will update the status
            case 'customer.subscription.updated':
                $webhookData = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                $subscriptionID = $webhookData['id'];
                $priceID = $webhookData['items']['data'][0]['price']['id'];
                $status = $webhookData['status'];

                $metadata = $webhookData['metadata'];
                $companyName = isset($metadata) && isset($metadata['company_name']) ? $metadata['company_name'] : config('app.rankup.comapny_name');

                $stripeSubscription = StripeSubscription::on($companyName)->where(['stripe_id' => $subscriptionID, 'price' => $priceID])->first();
                $user = User::on($companyName)->where('stripe_customer_id', $webhookData['customer'])->first();
                $plan = Plan::on($companyName)->where('stripe_price_id', $webhookData['items']['data'][0]['price']['id'])->first();
                $product = StripeProduct::on($companyName)->first();

                if ($stripeSubscription) {
                    $stripeSubscription->status = $status;
                    $stripeSubscription->period_end = date('Y-m-d H:i:s', $webhookData['current_period_end']);
                    $stripeSubscription->save();
                }

                $userPlan = UserPlan::on($companyName)->where(['stripe_subscription_id' => $stripeSubscription->id, 'plan_id' => $plan->id])->first();
                if ($userPlan) {
                    if ($status == 'active') {
                        $statusOne = 'active';
                    } else if ($status == 'incomplete') {
                        $statusOne = 'active';
                    } else if ($status == 'trialing') {
                        $statusOne = 'active';
                    } else {
                        $statusOne = 'expired';
                    }

                    $userPlan->stripe_status = $status;
                    $userPlan->status = $statusOne;
                    $userPlan->expiration = date('Y-m-d H:i:s', $webhookData['current_period_end']);
                    $userPlan->save();
                }
                UserPlan::on($companyName)->where(['stripe_subscription_id' => $stripeSubscription->id])->update(['stripe_status' => $status]);
                break;
            //If someone deletes the card we will also delete the card from DB
            case 'payment_method.detached':
                $webhookData = $event;
                $cardID = $webhookData->data->object->id;
                $customerID = $webhookData->data->object->customer;

                $metadata = $webhookData->data->object->metadata;
                $companyName = isset($metadata) && isset($metadata['company_name']) ? $metadata['company_name'] : config('app.rankup.comapny_name');
                
                $stripeCard = StripeCard::on($companyName)->where(['stripe_id' => $cardID])->first();
               
                if ($stripeCard) {
                    $stripeCard->whereHas('user', function ($q) use ($customerID) {
                        $q->where('stripe_customer_id', $customerID);
                    })->delete();
                }
                break;
            //If someone deletes the card we will also delete the card from DB
            case 'customer.source.deleted':
                //Log::error($event);
                break;
            default:
                // Unexpected event type
                $paymentMethod = $event->data->object;
                // Log::error('stripe webhook default case--' . $paymentMethod);
        }
        return http_response_code(200);

    }
}
