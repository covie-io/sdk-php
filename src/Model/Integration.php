<?php

namespace Covie\SDK\Model;

class Integration implements \JsonSerializable
{
    protected array $json;

    public function __construct(array $json)
    {
        $this->json = $json;
    }

    /**
     * Returns integration ID. e.g. in_abcdefg
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->json['id'];
    }

    /**
     * Returns integration key. e.g. ik_hijklmn
     *
     * @return string
     */
    public function getIntegrationKey(): string
    {
        return $this->json['key'];
    }

    public function jsonSerialize(): array
    {
        return $this->json;
    }
}