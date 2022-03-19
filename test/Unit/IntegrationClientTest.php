<?php

namespace Covie\SDK\Test\Unit;

use Covie\SDK\Test\Helper\PublicClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class IntegrationClientTest extends TestCase
{
    public function testCreatesIntegration(): void
    {
        $responseJson = [
            'id' => 'in_123456789',
            'key' => 'ik_123456789',
            'name' => 'test',
            'status' => 'active',
            'created_at' => '2022-03-14T22:20:56Z',
            'updated_at' => '2022-03-14T22:20:56Z',
            '_links' => [
                'self' => [
                    'href' => '/v1/integrations/in_123456789'
                ]
            ]
        ];

        $response = new Response(200, [], json_encode($responseJson));

        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);

        $historyContainer = [];
        $history = Middleware::history($historyContainer);
        $handler->push($history);

        $http = new Client(['handler' => $handler]);

        $integration = (new PublicClient($http))->integrations()->create('test');

        $this->assertCount(1, $historyContainer);
        $transaction = $historyContainer[0];

        $this->assertSame('POST', $transaction['request']->getMethod());
        $this->assertSame('integrations', (string) $transaction['request']->getUri());

        $this->assertSame('test', $integration->getName());
        $this->assertSame($responseJson, $integration->jsonSerialize());
        $this->assertSame('in_123456789', $integration->getId());
        $this->assertSame('ik_123456789', $integration->getIntegrationKey());
    }
}
