<?php

namespace Epifrin\DisposableEmailChecker\TrustDomain;

final class InvalidDomainException extends \InvalidArgumentException
{
    /** @var string */
    protected $message = 'Invalid domain';
}
