<?php

namespace Covie\SDK;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;

class Client
{
    protected ClientInterface $httpClient;

    protected const BASE_URI = 'https://api.covie.io/v1/';

    protected function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public static function createFromCredentials(
        string $clientId,
        string $clientSecret
    ): Client
    {
        return new Client(new HttpClient([
            'base_uri' => static::BASE_URI,
            'auth' => [
                static::assertValidClientId($clientId),
                $clientSecret,
            ]
        ]));
    }

    protected static function assertValidClientId(string $clientId): string
    {
        if (stripos($clientId, 'cl_') !== 0) {
            throw new \InvalidArgumentException('Client ID must start with cl_.');
        }

        return $clientId;
    }

    public function integrations(): IntegrationClient
    {
        return new IntegrationClient($this->httpClient);
    }

    public function policies(): PolicyClient
    {
        return new PolicyClient($this->httpClient);
    }
}
