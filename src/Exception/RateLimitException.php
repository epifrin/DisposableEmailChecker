<?php

namespace Epifrin\DisposableEmailChecker\Exception;

class RateLimitException extends \DomainException
{
    protected $message = 'You have reached the limit of free calls of Debounce Disposable API.';
}