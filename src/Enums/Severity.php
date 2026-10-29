<?php

declare(strict_types=1);

namespace Alibori\LaravelWatson\Enums;

enum Severity: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';
}
