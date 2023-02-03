<?php

namespace Epifrin\DisposableEmailChecker\Tests;

use Epifrin\DisposableEmailChecker\TrustDomain\InvalidDomainException;
use Epifrin\DisposableEmailChecker\TrustDomain\TrustDomain;
use PHPUnit\Framework\TestCase;

class TrustDomainTest extends TestCase
{
    /**
     * @dataProvider domainsProvider
     */
    public function testDomains(string $email, bool $result)
    {
        $trustDomain = new TrustDomain();
        $this->assertSame($trustDomain->isTrustDomain($email), $result);
    }

    public function domainsProvider()
    {
        return [
            'gmail.com' => ['gmail.com', true],
            'GMAIL.COM' => ['GMAIL.COM', true],
            'yahoo.com' => ['yahoo.com', true],
            'hotmail.com' => ['hotmail.com', true],
            'aol.com' => ['aol.com', true],
            'msn.com' => ['msn.com', true],
            'testgmail.com' => ['testgmail.com', false],
            'gmail.com.test' => ['gmail.com.test', false],
        ];
    }

    public function testAddTrustDomain(): void
    {
        $domain = 'domain.com';
        $trustDomain = new TrustDomain();
        $trustDomain->addDomains([$domain, 'domain2.com']);
        $this->assertTrue($trustDomain->isTrustDomain($domain));
    }

    /**
     * @dataProvider incorrectDomainsProvider
     */
    public function testAddIncorrectDomain($incorrectDomain)
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('Domain ' . $incorrectDomain . ' is incorrect');
        $trustDomain = new TrustDomain();
        $trustDomain->addDomains(['domain.com', $incorrectDomain]);
    }

    public function incorrectDomainsProvider()
    {
        return [
            'incorrect' => ['incorrect'],
            '12312312' => ['12312312'],
            '...' => ['...'],
        ];
    }

    public function testAddIncorrectStringDomain()
    {
        $this->expectException(InvalidDomainException::class);
        $this->expectExceptionMessage('Domain is incorrect');
        $trustDomain = new TrustDomain();
        $trustDomain->addDomains([[]]);
    }
}