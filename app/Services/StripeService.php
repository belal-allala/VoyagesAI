<?php

namespace App\Services;

use Stripe\StripeClient; 

class StripeService
{
    public function getStripeClient(): StripeClient
    {
        return new StripeClient(config('services.stripe.secret'));
    }
}