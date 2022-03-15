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