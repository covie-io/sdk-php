<?php

namespace Covie\SDK;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;

class Client
{
    protected ClientInterface $httpClient;

    protected function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public static function createFromCredentials(
        string $clientId,
        string $clientSecret,
        string $baseUri = 'https://api.covie.io/v1/'
    ): Client
    {
        if (stripos($clientId, 'cl_') !== 0) {
            throw new \InvalidArgumentException('Client ID must start with cl_.');
        }

        return new Client(new HttpClient([
            'base_uri' => $baseUri,
            'auth' => [
                'username' => $clientId,
                'password' => $clientSecret,
            ]
        ]));
    }

    public function integrations(): IntegrationClient
    {
        return new IntegrationClient($this->httpClient); // factory
    }

    public function policies(): PolicyClient
    {
        return new PolicyClient($this->httpClient); // factory
    }
}