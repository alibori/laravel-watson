# Laravel Watson

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alibori/laravel-watson.svg?style=flat-square)](https://packagist.org/packages/alibori/laravel-watson)
[![Tests](https://github.com/alibori/laravel-watson/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/alibori/laravel-watson/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/alibori/laravel-watson/actions/workflows/phpstan.yml/badge.svg?branch=main)](https://github.com/alibori/laravel-watson/actions/workflows/phpstan.yml)
[![Code Style](https://github.com/alibori/laravel-watson/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/alibori/laravel-watson/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/alibori/laravel-watson.svg?style=flat-square)](https://packagist.org/packages/alibori/laravel-watson)

Laravel Watson watches your application's exceptions, uses the Laravel AI SDK to diagnose the likely cause and fix, and notifies you only when it's actually worth knowing about.

## Installation

You can install the package via composer:

```bash
composer require alibori/laravel-watson
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="watson-config"
```

## Usage

### 1. Configure the AI provider

Watson uses the [Laravel AI SDK](https://github.com/laravel/ai). Set up a provider and its API key as described in its docs (e.g. `OPENAI_API_KEY` or `ANTHROPIC_API_KEY` in your `.env`).

### 2. Configure Watson

```dotenv
WATSON_ENABLED=true
WATSON_CHANNEL=mail
WATSON_ROUTE=dev-team@example.com
```

- `WATSON_ENABLED`: master switch. Set to `false` locally or in tests.
- `WATSON_CHANNEL`: notification channel used to deliver the analysis. **For now only works with mail**.
- `WATSON_ROUTE`: destination for that channel (an email address for `mail`). Watson does nothing if it's empty.

### 3. Hook it into your exception handler

In `bootstrap/app.php`:

```php
use Alibori\LaravelWatson\LaravelWatson;
use Illuminate\Foundation\Configuration\Exceptions;

->withExceptions(function (Exceptions $exceptions) {
    $exceptions->report(function (Throwable $e) {
        LaravelWatson::handle($e);
    });
})
```

### 4. Run a queue worker

The analysis runs in a queued job, so your requests are never slowed down by the AI call:

```bash
php artisan queue:work
```

Each notification includes a summary, the likely cause, a suggested fix and a severity (`low`, `medium`, `high`, `critical`).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Axel Libori Roch](https://github.com/alibori)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
