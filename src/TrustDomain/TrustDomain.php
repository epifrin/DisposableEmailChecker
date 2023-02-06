<?php

declare(strict_types=1);

namespace Epifrin\DisposableEmailChecker\TrustDomain;

class TrustDomain
{
    /** @var array<string> */
    private array $trustDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'aol.com', 'msn.com', 'icloud.com', 'zoho.com', 'mail.com'];

    public function isTrustDomain(string $domain): bool
    {
        return in_array(\mb_strtolower($domain), $this->trustDomains, true);
    }

    /**
     * @param array<string> $trustDomains
     */
    public function addDomains(array $trustDomains): void
    {
        foreach ($trustDomains as &$domain) {
            if (!is_string($domain)) {
                throw new InvalidDomainException('Domain is incorrect');
            }
            $domain = \mb_strtolower($domain);
            if (!preg_match('/^([a-z0-9]+\.)+[a-z]{2,6}$/', $domain)) {
                throw new InvalidDomainException('Domain ' . $domain . ' is incorrect');
            }
        }
        unset($domain);

        $this->trustDomains = array_merge($this->trustDomains, $trustDomains);
    }
}
