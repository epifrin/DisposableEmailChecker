<?php

namespace Epifrin\DisposableEmailChecker\Email;

final class InvalidEmailException extends \InvalidArgumentException
{
    /** @var string */
    protected $message = 'Invalid email';
}
