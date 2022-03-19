<?php

namespace Covie\SDK\Test\Unit;

use Covie\SDK\Model\DocumentHref;
use Covie\SDK\Model\DocumentType;
use Covie\SDK\Model\Policy;
use Covie\SDK\Test\Helper;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class PolicyClientTest extends TestCase
{
    public function testGetsPolicy(): void
    {
        $samplePolicy = file_get_contents(__DIR__ . '/../assets/sample_policy.json', false);
        $policyResponse = new Response(200, [], $samplePolicy);

        $mock = new MockHandler([$policyResponse]);
        $handler = HandlerStack::create($mock);

        $historyContainer = [];
        $history = Middleware::history($historyContainer);
        $handler->push($history);

        $http = new Client(['handler' => $handler]);

        $sdkClient = new Helper\PublicClient($http);

        $policy = $sdkClient->policies()->get('po_y7v2nqqpyitfctqq');

        $this->assertCount(1, $historyContainer);
        $transaction = $historyContainer[0];

        $this->assertSame('GET', $transaction['request']->getMethod());
        $this->assertSame('policies/po_y7v2nqqpyitfctqq', (string) $transaction['request']->getUri());

        $this->assertSame('po_y7v2nqqpyitfctqq', $policy->getId());

        $this->assertCount(4, $policy->getDocumentLinks());
        foreach ($policy->getDocumentLinks() as $documentLink) {
            $this->assertInstanceOf(DocumentHref::class, $documentLink);
            $this->assertSame('application/pdf', $documentLink->getContentType());
        }

        $this->assertSame(
            $policy->jsonSerialize()['_links']['documents'][0],
            $policy->getDocumentLinks()[0]->jsonSerialize()
        );
        $this->assertJsonStringEqualsJsonString($samplePolicy, json_encode($policy->jsonSerialize()));
    }

    public function testGetsPolicyDocuments(): void
    {
        $policyData = [
            'id' => 'po_1234567890',
            '_links' => [
                'documents' => [
                    [
                        'type' => 'cancellation',
                        'content_type' => 'application/pdf',
                        'href' => '/v1/policies/po_1234567890/documents/cancellation'
                    ],
                    [
                        'type' => 'declaration',
                        'content_type' => 'application/pdf',
                        'href' => '/v1/policies/po_1234567890/documents/declaration'
                    ],
                    [
                        'type' => 'declaration',
                        'content_type' => 'application/pdf',
                        'href' => '/v1/policies/po_1234567890/documents/declaration_2'
                    ],
                ]
            ]
        ];
        $policy = new Policy($policyData);


        // Set up a response to return a PDF
        $samplePdf = file_get_contents(__DIR__ . '/../assets/declaration.pdf');
        $pdfResponse = new Response(200, [
            'content-type' => 'application/pdf'
        ], $samplePdf);

        // Create Mock Handler and HandlerStack
        $mock = new MockHandler([$pdfResponse]);
        $handler = HandlerStack::create($mock);

        // Set up Guzzle's History Middleware for introspection of generated request after calling the API
        $historyContainer = [];
        $history = Middleware::history($historyContainer);
        $handler->push($history);

        // Create client
        $http = new Client(['handler' => $handler]);

        // Get the document of a given type
        $sdkClient = new Helper\PublicClient($http);
        $pdfData = $sdkClient->policies()->getLatestDocumentOfType($policy, 'declaration');

        $this->assertCount(1, $historyContainer);
        $transaction = $historyContainer[0];

        $this->assertSame('GET', $transaction['request']->getMethod());
        $this->assertSame('/v1/policies/po_1234567890/documents/declaration', (string) $transaction['request']->getUri());
        $this->assertSame($samplePdf, $pdfData);
    }

    public function testDocumentRetrievalFailsIfNoMatchingDocumentTypeIsFound(): void
    {
        $policyData = [
            'id' => 'po_1234567890',
            '_links' => [
                'documents' => [
                    [
                        'type' => 'cancellation',
                        'content_type' => 'application/pdf',
                        'href' => '/v1/policies/po_1234567890/documents/cancellation'
                    ],
                    [
                        'type' => 'declaration',
                        'content_type' => 'application/pdf',
                        'href' => '/v1/policies/po_1234567890/documents/declaration'
                    ],
                    [
                        'type' => 'declaration',
                        'content_type' => 'application/pdf',
                        'href' => '/v1/policies/po_1234567890/documents/declaration_2'
                    ],
                ]
            ]
        ];
        $policy = new Policy($policyData);

        // Create Mock Handler and HandlerStack; mock handler has no responses in the queue, so if it's called,
        // it will throw, failing the test (which is what we want; this scenario should never make an HTTP call)
        $mock = new MockHandler([]);
        $handler = HandlerStack::create($mock);

        // Create HTTP client
        $http = new Client(['handler' => $handler]);

        $sdkClient = new Helper\PublicClient($http);
        $this->expectExceptionMessage('Could not find a document of the specified type for this policy');
        $this->expectException('RuntimeException');
        $sdkClient->policies()->getLatestDocumentOfType($policy, DocumentType::INSURANCE_CARD);
    }
}