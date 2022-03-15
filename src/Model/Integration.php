<?php

namespace Covie\SDK\Model;

class Integration implements \JsonSerializable
{
    protected array $json;

    public function __construct(array $json)
    {
        $this->json = $json;
    }

    public function getId(): string
    {
        return $this->json['id'];
    }

    public function getIntegrationKey(): string
    {
        return $this->json['key'];
    }

    public function jsonSerialize(): array
    {
        return $this->json;
    }
}