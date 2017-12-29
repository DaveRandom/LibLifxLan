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

    abstract public function getTypeId(): int;
    abstract public function getWireSize(): int;
}
