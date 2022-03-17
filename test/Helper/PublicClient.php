<?php

namespace Covie\SDK\Test\Helper;

use Covie\SDK\Client;

class PublicClient extends Client
{
    public function __construct(\GuzzleHttp\ClientInterface $httpClient)
    {
        parent::__construct($httpClient);
    }
}