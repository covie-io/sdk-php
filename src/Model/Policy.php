<?php

namespace Covie\SDK\Model;

class Policy implements \JsonSerializable
{
    protected array $json;

    public function __construct(array $json)
    {
        $this->json = $json;
    }

    /**
     * Returns policy ID. e.g. po_abcdefg
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->json['id'];
    }

    /**
     * Returns an array of document URLs that can be downloaded from a given policy.
     *
     * @return DocumentHref[]
     */
    public function getDocumentLinks(): array
    {
        return array_map(fn (array $item) => new DocumentHref($item), $this->json['_links']['documents'] ?? []);
    }

    public function jsonSerialize(): array
    {
        return $this->json;
    }
}