<?php

return [
    /*
     * Copyleaks installation keys
     */

    'email' => env('COPYLEAKS_EMAIL'),
    'key' => env('COPYLEAKS_KEY'),
    'sandbox' => env('COPYLEAKS_SANDBOX', true),
    'webhookBase' => env('COPYLEAKS_WEBHOOK_BASE'),
];
