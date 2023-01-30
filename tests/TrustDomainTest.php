<?php

namespace Epifrin\DisposableEmailChecker\Tests;

use Epifrin\DisposableEmailChecker\Checker\CheckerInterface;
use Epifrin\DisposableEmailChecker\DisposableEmailChecker;
use PHPUnit\Framework\TestCase;

class TrustDomainTest extends TestCase
{
    public function testTrustDomain(): void
    {
        $email = 'test@gmail.com';

        $checkerMock = $this->createMock(CheckerInterface::class);
        $checkerMock
            ->expects($this->never())
            ->method('isEmailDisposable');

        $disposableEmailChecker = new DisposableEmailChecker($checkerMock);

        $this->assertFalse($disposableEmailChecker->isEmailDisposable($email));
    }

    /**
     * @dataProvider domainsProvider
     */
    public function testDomains(string $email, bool $result)
    {
        $checkerMock = $this->createMock(CheckerInterface::class);
        $checkerMock
            ->method('isEmailDisposable')
            ->willReturn(true);
        $disposableEmailChecker = new DisposableEmailChecker($checkerMock);
        $this->assertSame($disposableEmailChecker->isEmailDisposable($email), $result);
    }

    public function domainsProvider()
    {
        return [
            'gmail.com' => ['admin@gmail.com', false],
            'testgmail.com' => ['admin@testgmail.com', true],
            'gmail.com.test' => ['admin@gmail.com.test', true],
        ];
    }

    public function testAddTrustDomain(): void
    {
        $email = 'test@domain.com';

        $checkerMock = $this->createMock(CheckerInterface::class);
        $checkerMock
            ->expects($this->never())
            ->method('isEmailDisposable');

        $disposableEmailChecker = new DisposableEmailChecker($checkerMock);

        $disposableEmailChecker->addTrustDomains(['domain.com']);

        $this->assertFalse($disposableEmailChecker->isEmailDisposable($email));
    }
}