<?php

namespace Covie\SDK;

use GuzzleHttp\ClientInterface;
use Covie\SDK\Model\Policy;

class PolicyClient
{
    protected ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Requests a policy based on ID. Returns a new Policy object response wrapper.
     *
     * @param string $id
     * @return Policy
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function get(string $id): Policy
    {
        return new Policy(json_decode((string) $this->httpClient->request(
            'GET',
            'policies/' . $id
        )->getBody(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function getLatestDocumentOfType(Policy $policy, string $documentType): string
    {
        foreach ($policy->getDocumentHrefs() as $href) {
            if ($href->getType() === $documentType) {
                return (string) $this->httpClient->request(
                    'GET',
                    $href->getUrl()
                )->getBody();
            }
        }

        throw new \RuntimeException('Could not find a document of the specified type for this policy');
    }
}
