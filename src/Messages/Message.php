<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages;

abstract class Message
{
    public const REQUIRE_ACK = 0b01;
    public const REQUIRE_RESPONSE = 0b10;

    private $responsePattern;

    protected function __construct(int $responsePattern)
    {
        $this->responsePattern = $responsePattern;
    }

    public function isAckRequired(): bool
    {
        return (bool)($this->responsePattern & self::REQUIRE_ACK);
    }

    public function isResponseRequired(): bool
    {
        return (bool)($this->responsePattern & self::REQUIRE_RESPONSE);
    }

    abstract public function getTypeId(): int;
}
