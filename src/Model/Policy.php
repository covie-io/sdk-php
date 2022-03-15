<?php

namespace Covie\SDK\Model;

class Policy implements \JsonSerializable
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

    /**
     * @return DocumentHref[]
     */
    public function getDocumentHrefs(): array
    {
        return array_map(fn (array $item) => new DocumentHref($item), $this->data['_links']['documents'] ?? []);
    }

    public function jsonSerialize(): array
    {
        return $this->json;
    }
}