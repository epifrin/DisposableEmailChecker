<?php

namespace Epifrin\DisposableEmailChecker\Tests;

use Epifrin\DisposableEmailChecker\Cache\ArrayCache;
use Epifrin\DisposableEmailChecker\Checker\CheckerInterface;
use Epifrin\DisposableEmailChecker\DisposableEmailChecker;
use Epifrin\DisposableEmailChecker\Exception\RateLimitException;
use PHPUnit\Framework\TestCase;

class DisposableEmailCheckerTest extends TestCase
{

    public function testAlreadySearchedEmail()
    {
        $email = 'test@mailinator.com';

        $checker = $this->createMock(CheckerInterface::class);
        $checker
            ->expects($this->once())
            ->method('isEmailDisposable')
            ->willReturn(true);

        $disposableApi = new DisposableEmailChecker(new ArrayCache(), $checker);

        $this->assertTrue($disposableApi->isEmailDisposable($email));
        // this time Debounce Api will not call
        $this->assertTrue($disposableApi->isEmailDisposable($email));
    }

    public function testTrustDomain()
    {
        $checker = $this->createMock(CheckerInterface::class);
        $checker
            ->expects($this->never())
            ->method('isEmailDisposable')
            ->willReturn(true);
        $disposableEmailChecker = new DisposableEmailChecker(new ArrayCache(), $checker);

        $disposableEmailChecker->addTrustDomains(['domain.com']);

        $this->assertFalse($disposableEmailChecker->isEmailDisposable('foo@domain.com'));
    }

    public function testSetRateLimiting()
    {
        $this->expectException(RateLimitException::class);
        $checker = $this->createMock(CheckerInterface::class);
        $checker
            ->expects($this->once())
            ->method('isEmailDisposable')
            ->will($this->throwException(new RateLimitException()));
        $disposableEmailChecker = new DisposableEmailChecker(new ArrayCache(), $checker);

        $disposableEmailChecker->isEmailDisposable('foo@mailinator.com');
    }

    public function testRateLimiting()
    {
        $this->expectException(RateLimitException::class);

        $checker = $this->createMock(CheckerInterface::class);
        $checker
            ->expects($this->never())
            ->method('isEmailDisposable');

        $cache = new ArrayCache();
        $cache->set('rate.limit.reached', true);
        $disposableEmailChecker = new DisposableEmailChecker($cache, $checker);

        $disposableEmailChecker->isEmailDisposable('foo@mailinator.com');
    }
}
