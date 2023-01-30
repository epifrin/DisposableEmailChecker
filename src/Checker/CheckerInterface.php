<?php

namespace Epifrin\DisposableEmailChecker\Checker;

interface CheckerInterface
{
    public function isEmailDisposable(string $emailAddress): bool;
}