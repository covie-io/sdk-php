<?php

namespace Covie\SDK\Test\Unit;

use PHPUnit\Framework\TestCase;
use Covie\SDK\Client;

class SDKClientTest extends TestCase
{
    public function testAssertSDKClientType(): void
    {
        $sdkClient = Client::createFromCredentials('foo', 'bar');
        $this->assertInstanceOf(\Covie\SDK\Client::class, $sdkClient);
    }
}