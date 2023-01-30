<?php

namespace Epifrin\DisposableEmailChecker\Email;

class InvalidEmailException extends \InvalidArgumentException
{
    /** @var string */
    protected $message = 'Invalid email';
}