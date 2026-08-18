<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Notification Channel
    |--------------------------------------------------------------------------
    |
    | The Laravel notification channel Watson will use to deliver exception
    | analysis (e.g. "slack", "mail", "discord"...). This must match a
    | channel that Laravel's notification system knows how to route.
    |
    */

    'channel' => env('LARAVEL_WATSON_CHANNEL', 'mail'),

    /*
    |--------------------------------------------------------------------------
    | Notification Route
    |--------------------------------------------------------------------------
    |
    | The destination for the chosen channel — a webhook URL for Slack,
    | an email address for mail, etc. Passed to Notification::route().
    |
    */

    'route' => env('LARAVEL_WATSON_ROUTE'),

];
