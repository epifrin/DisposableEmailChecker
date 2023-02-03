<?php

declare(strict_types=1);

namespace Epifrin\DisposableEmailChecker\Email;

final class Email
{
    private string $email;
    private string $domain;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
        $this->email = \mb_strtolower($email);
        $this->domain = explode('@', $email)[1];
    }

    public function email(): string
    {
        return $this->email;
    }

    public function domain(): string
    {
        return $this->domain;
    }
}
