<?php

namespace Covie\SDK;

use GuzzleHttp\ClientInterface;
use Covie\SDK\Model\Integration;

class IntegrationClient
{
    protected ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Returns a new Integration object response wrapper.
     *
     * @param string $name
     * @return Integration
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function create(string $name): Integration
    {
        return new Integration(json_decode((string) $this->httpClient->request(
            'POST',
            'integrations',
            [
                'json' => [
                    'name' => $name
                ],
            ]
        )->getBody(), true, 512, JSON_THROW_ON_ERROR));
    }
}