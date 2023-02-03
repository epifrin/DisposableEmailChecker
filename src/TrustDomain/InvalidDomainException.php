<?php

namespace Epifrin\DisposableEmailChecker\TrustDomain;

class InvalidDomainException extends \InvalidArgumentException
{
    /** @var string */
    protected $message = 'Invalid domain';
}