<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Master switch for Watson. When disabled, exceptions are still handled
    | by Laravel as usual, but Watson won't investigate them or send any
    | notification. Useful to turn it off locally or in testing.
    |
    */

    'enabled' => env('WATSON_ENABLED', true),

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

    'channel' => env('WATSON_CHANNEL', 'mail'),

    /*
    |--------------------------------------------------------------------------
    | Notification Route
    |--------------------------------------------------------------------------
    |
    | The destination for the chosen channel — a webhook URL for Slack,
    | an email address for mail, etc. Passed to Notification::route().
    |
    */

    'route' => env('WATSON_ROUTE', null),

];
