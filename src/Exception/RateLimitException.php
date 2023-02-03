<?php

namespace Epifrin\DisposableEmailChecker\Exception;

final class RateLimitException extends \DomainException
{
    /** @var string */
    protected $message = 'You have reached the limit of free calls of Debounce Disposable API.';
}
