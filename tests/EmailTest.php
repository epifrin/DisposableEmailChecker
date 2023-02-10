<?php

namespace Epifrin\DisposableEmailChecker\Tests;

use Epifrin\DisposableEmailChecker\Email\Email;
use Epifrin\DisposableEmailChecker\Email\InvalidEmailException;

class EmailTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider correctEmailProvider
     */
    public function testCorrectEmail($emailAddress, $domain): void
    {
        $email = new Email($emailAddress);
        $this->assertEquals($emailAddress, $email->email());
        $this->assertEquals($domain, $email->domain());
    }

    public function correctEmailProvider(): array
    {
        return [
            'test@gmail.com' => ['test@gmail.com', 'gmail.com'],
            'test.test@gmail.com' => ['test.test@gmail.com', 'gmail.com'],
            'test.test+123@gmail.com' => ['test.test+123@gmail.com', 'gmail.com'],
        ];
    }

    /**
     * @dataProvider invalidEmailProvider
     */
    public function testInvalidEmail($invalidEmail): void
    {
        $this->expectException(InvalidEmailException::class);
        new Email($invalidEmail);
    }

    public function invalidEmailProvider(): array
    {
        return [
            'test.com' => ['test.com'],
            '@test.com' => ['@test.com'],
            'sdfsd@domain' => ['sdfsd@domain'],
        ];
    }
}
