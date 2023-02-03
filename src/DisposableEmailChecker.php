<?php

declare(strict_types=1);

namespace Epifrin\DisposableEmailChecker;

use Epifrin\DisposableEmailChecker\Cache\ArrayCache;
use Epifrin\DisposableEmailChecker\Checker\CheckerInterface;
use Epifrin\DisposableEmailChecker\Checker\DebounceDisposableApiChecker;
use Epifrin\DisposableEmailChecker\Email\Email;
use Epifrin\DisposableEmailChecker\Exception\RateLimitException;
use Epifrin\DisposableEmailChecker\TrustDomain\TrustDomain;
use Psr\SimpleCache\CacheInterface;

final class DisposableEmailChecker implements DisposableEmailCheckerInterface
{
    public int $cacheTimeout = 24 * 60 * 60;

    private TrustDomain $trustDomain;

    public function __construct(
        private readonly CacheInterface $cache = new ArrayCache(),
        private readonly CheckerInterface $checker = new DebounceDisposableApiChecker()
    ) {
        $this->trustDomain = new TrustDomain();
    }

    public function isEmailDisposable(string $emailAddress): bool
    {
        $email = new Email($emailAddress);

        if ($this->trustDomain->isTrustDomain($email->domain())) {
            return false;
        }

        if ($this->alreadyFound($email->domain())) {
            return $this->getResultFromCache($email->domain());
        }

        // check with Debounce Disposable API or custom checker
        return $this->check($email);
    }

    private function alreadyFound(string $domain): bool
    {
        return $this->cache->has('domain=' . $domain);
    }

    private function check(Email $email): bool
    {
        $this->checkRateLimiting();

        try {
            $isDisposable = $this->checker->isEmailDisposable($email->email());
            $this->saveResultToCache($email, $isDisposable);
        } catch (RateLimitException $e) {
            $this->cache->set('rate.limit.reached', true);
            throw $e;
        }
        return $isDisposable;
    }

    private function getResultFromCache(string $domain): bool
    {
        return (bool)$this->cache->get('domain=' . $domain, false);
    }

    /**
     * @param array<string> $trustDomains format ['domain.com', 'domain.net']
     */
    public function addTrustDomains(array $trustDomains): self
    {
        $this->trustDomain->addDomains($trustDomains);
        return $this;
    }

    private function saveResultToCache(Email $email, bool $isDisposable): void
    {
        $this->cache->set('domain=' . $email->domain(), $isDisposable, $this->cacheTimeout);
    }

    private function checkRateLimiting(): void
    {
        if (true === $this->cache->get('rate.limit.reached')) {
            throw new RateLimitException();
        }
    }
}
