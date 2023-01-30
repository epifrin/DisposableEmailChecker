<?php

namespace Epifrin\DisposableEmailChecker\Checker;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

/**
 * Class for sending request to 3rdpart Debounce Disposable API https://debounce.io/free-disposable-check-api/
 * @see https://debounce.io/free-disposable-check-api/
 */
final class DebounceDisposableApiChecker implements CheckerInterface
{
    private const DEBOUNCE_DISPOSABLE_API_HOST = 'https://disposable.debounce.io/';

    public function __construct(private readonly ClientInterface $client = new Client())
    {
    }

    public function isEmailDisposable(string $emailAddress): bool
    {
        return $this->sendRequest($emailAddress);
    }

    private function sendRequest(string $email): bool
    {
        $request = new Request('GET', self::DEBOUNCE_DISPOSABLE_API_HOST . '?email=' . $email);
        //try {
            $response = $this->client->sendRequest($request);
            if ($response->getStatusCode() === 200) {
                $body = (array)\json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
                if (isset($body['disposable']) && $body['disposable'] === 'true') {
                    return true;
                }
            }
        //} catch (ClientExceptionInterface $e) {

        //}
        return false;
    }
}