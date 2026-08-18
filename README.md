# Laravel Watson

## Package under development!

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alibori/laravel-watson.svg?style=flat-square)](https://packagist.org/packages/alibori/laravel-watson)
[![GitHub Tests Action Status](https://github.com/spatie/package-laravel-watson-laravel/actions/workflows/run-tests.yml/badge.svg)](https://github.com/alibori/laravel-watson/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://github.com/spatie/package-laravel-watson-laravel/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/alibori/laravel-watson/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alibori/laravel-watson.svg?style=flat-square)](https://packagist.org/packages/alibori/laravel-watson)

Laravel Watson watches your application's exceptions, uses the Laravel AI SDK to diagnose the likely cause and fix, and notifies you only when it's actually worth knowing about.

## Installation

You can install the package via composer:

```bash
composer require alibori/laravel-watson
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-watson-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-watson-config"
```

This is the contents of the published config file:

```php
return [
];
```

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
