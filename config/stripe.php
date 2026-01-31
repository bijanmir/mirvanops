<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    
    'prices' => [
        'starter' => env('STRIPE_PRICE_STARTER'),
        'professional' => env('STRIPE_PRICE_PROFESSIONAL'),
        'business' => env('STRIPE_PRICE_BUSINESS'),
    ],
    
    'plans' => [
        'free' => [
            'name' => 'Free',
            'price' => 0,
            'unit_limit' => 5,
            'features' => ['Up to 5 units', 'Basic reporting', 'Email support'],
        ],
        'starter' => [
            'name' => 'Starter',
            'price' => 19,
            'unit_limit' => 25,
            'features' => ['Up to 25 units', 'Full reporting', 'PDF exports', 'Priority email support'],
        ],
        'professional' => [
            'name' => 'Professional',
            'price' => 49,
            'unit_limit' => 100,
            'features' => ['Up to 100 units', 'Full reporting', 'PDF exports', 'Document storage', 'Priority support'],
        ],
        'business' => [
            'name' => 'Business',
            'price' => 99,
            'unit_limit' => 500,
            'features' => ['Up to 500 units', 'Full reporting', 'PDF exports', 'Document storage', 'API access', 'Phone support'],
        ],
    ],
];