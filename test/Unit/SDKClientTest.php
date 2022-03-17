<?php

namespace Covie\SDK\Test\Unit;

use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Covie\SDK\Client;
use ReflectionObject;

class SDKClientTest extends TestCase
{
    public function testCreateFromCredentialsBuildsProperlyConfiguredClient(): void
    {
        $sdkClient = Client::createFromCredentials('cl_foo', 'bar');

        $reflection = new ReflectionObject($sdkClient);

        // get $httpClient property, set visibility to public
        $sdkGuzzleProperty = $reflection->getProperty('httpClient');
        $sdkGuzzleProperty->setAccessible(true);

        // capture Guzzle client instance, *not an instance of ReflectionProperty*
        $guzzle = $sdkGuzzleProperty->getValue($sdkClient);

        $guzzleReflection = new ReflectionObject($guzzle);

        // get Guzzle client's config property which contains base_uri and auth values
        $guzzleConfigProperty = $guzzleReflection->getProperty('config');
        $guzzleConfigProperty->setAccessible(true);
        $guzzleConfig = $guzzleConfigProperty->getValue($guzzle);

        /** @var Uri $uri */
        $uri = $guzzleConfig['base_uri'];
        $this->assertEquals('https://api.covie.io/v1/', (string) $uri);

        $this->assertEquals(['cl_foo', 'bar'], $guzzleConfig['auth']);
    }

    public function testCreateFromCredentialsFailsOnInvalidClientId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Client::createFromCredentials('foo', '');
    }
}