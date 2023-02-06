<?php

namespace Epifrin\DisposableEmailChecker\Tests;

use Epifrin\DisposableEmailChecker\Cache\ArrayCache;
use Epifrin\DisposableEmailChecker\Checker\CheckerInterface;
use Epifrin\DisposableEmailChecker\Checker\DebounceDisposableApiChecker;
use Epifrin\DisposableEmailChecker\DisposableEmailChecker;
use Epifrin\DisposableEmailChecker\Exception\RateLimitException;
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
            new Response(
                200,
                ['application' => 'json'],
                \json_encode(['disposable' => 'true'])
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $disposableApi = new DebounceDisposableApiChecker($client);
        $this->assertTrue($disposableApi->isEmailDisposable('test@mailnator.com'));
    }

    public function testReachRateLimiting()
    {
        $this->expectException(RateLimitException::class);
        $mock = new MockHandler([
            new Response(
                200,
                ['application' => 'json'],
                \json_encode(['error' => 'rate limit reached'])
        )]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $disposableApi = new DebounceDisposableApiChecker($client);
        $this->assertTrue($disposableApi->isEmailDisposable('test@mailnator.com'));
    }

    public function testHttpError()
    {
        $mock = new MockHandler([
                                    new Response(
                                        401,
                                        ['application' => 'json'],
                                    )]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $disposableApi = new DebounceDisposableApiChecker($client);
        $this->assertFalse($disposableApi->isEmailDisposable('test@mailnator.com'));
    }
}
