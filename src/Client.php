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

    /**
     * Creates new SDK Client object based on client ID and secret, utilizing GuzzleHttp Client.
     * Verifies client ID is correct.
     * Uses basic HTTP Authentication presently.
     * TODO: expand to use Bearer tokens.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @return Client
     */
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

    /**
     * Returns a GuzzleHttp Client that calls the /integrations endpoint.
     * Factory method.
     *
     * @return IntegrationClient
     */
    public function integrations(): IntegrationClient
    {
        return new IntegrationClient($this->httpClient);
    }

    /**
     * Returns a GuzzleHttp Client that calls the /policies endpoint.
     * Factory method.
     *
     * @return PolicyClient
     */
    public function policies(): PolicyClient
    {
        return new PolicyClient($this->httpClient);
    }
}
