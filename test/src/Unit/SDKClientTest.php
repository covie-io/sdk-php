<?php

namespace Covie\SDK\Test\Unit;

use PHPUnit\Framework\TestCase;

class SDKClientTest extends TestCase
{
    public function testAssertSDKClientType(): void
    {
        $sdkClient = new \Covie\SDK\Client();
        $this->assertEquals(\Covie\SDK\Client::class, $sdkClient);
    }
}