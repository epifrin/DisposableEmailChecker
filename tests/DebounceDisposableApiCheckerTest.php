<?php

namespace Epifrin\DisposableEmailChecker\Tests;

use Epifrin\DisposableEmailChecker\Cache\ArrayCache;
use Epifrin\DisposableEmailChecker\Checker\CheckerInterface;
use Epifrin\DisposableEmailChecker\Checker\DebounceDisposableApiChecker;
use Epifrin\DisposableEmailChecker\DisposableEmailChecker;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class DebounceDisposableApiCheckerTest extends TestCase
{
    public function testDisposableEmail()
    {
        $mock = new MockHandler([
            new Response(200, ['application' => 'json'], \json_encode(['disposable' => 'true']))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $disposableApi = new DebounceDisposableApiChecker($client);
        $this->assertTrue($disposableApi->isEmailDisposable('test@mailnator.com'));
    }

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
}