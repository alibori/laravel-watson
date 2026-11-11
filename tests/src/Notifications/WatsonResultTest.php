<?php

declare(strict_types=1);

use Alibori\LaravelWatson\DTO\WatsonResult as WatsonResultDTO;
use Alibori\LaravelWatson\Notifications\WatsonResult;
use Illuminate\Notifications\AnonymousNotifiable;

function watsonNotification(): WatsonResult
{
    return new WatsonResult(new WatsonResultDTO(
        summary: 'Null user',
        cause: 'Guest request',
        fix: 'Add auth middleware',
        severity: 'high',
    ));
}

test('notification is delivered through the configured channel', function () {
    config()->set('watson.channel', 'slack');

    expect(watsonNotification()->via(new AnonymousNotifiable))->toBe(['slack']);
});

test('notification mail contains the diagnosis', function () {
    $mail = watsonNotification()->toMail(new AnonymousNotifiable);

    expect($mail->subject)->toBe('**Laravel Watson result** [high]')
        ->and($mail->introLines)->toBe([
            '**Null user**',
            '**Why:** Guest request',
            '**Fix:** Add auth middleware',
        ]);
});
