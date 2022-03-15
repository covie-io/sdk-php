<?php

namespace Covie\SDK\Model;

class DocumentHref implements \JsonSerializable
{
    protected array $json;

    public function __construct(array $json)
    {
        $this->json = $json;
    }

    public function getType(): string
    {
        return $this->json['type'];
    }

    public function getUrl(): string
    {
        return $this->json['href'];
    }

    public function getContentType(): string
    {
        return $this->json['content_type'];
    }

    public function jsonSerialize()
    {
        return $this->json;
    }
}