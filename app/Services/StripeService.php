<?php

namespace App\Services;

use Stripe\StripeClient; // Importez StripeClient

class StripeService
{
    /**
     * Get a Stripe client instance.
     *
     * @return \Stripe\StripeClient
     */
    public function getStripeClient(): StripeClient
    {
        return new StripeClient(config('services.stripe.secret'));
    }
}