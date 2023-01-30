<?php

namespace Epifrin\DisposableEmailChecker;

interface DisposableEmailCheckerInterface
{
    public function isEmailDisposable(string $emailAddress): bool;

    /**
     * @param array<string> $trustDomains format ['domain.com', 'domain.net']
     */
    public function addTrustDomains(array $trustDomains): self;
}