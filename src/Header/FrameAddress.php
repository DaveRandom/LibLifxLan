<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

use DaveRandom\Network\MacAddress;

final class FrameAddress
{
    public const WIRE_SIZE = 16;

    private $target;
    private $ackRequired;
    private $responseRequired;
    private $sequenceNo;

    public function __construct(?MacAddress $target, bool $ackRequired, bool $responseRequired, int $sequenceNo)
    {
        $this->target = $target ?? new MacAddress(0, 0, 0, 0, 0, 0);
        $this->ackRequired = $ackRequired;
        $this->responseRequired = $responseRequired;
        $this->sequenceNo = $sequenceNo;
    }

    public function getTarget(): MacAddress
    {
        return $this->target;
    }

    public function isAckRequired(): bool
    {
        return $this->ackRequired;
    }

    public function isResponseRequired(): bool
    {
        return $this->responseRequired;
    }

    public function getSequenceNo(): int
    {
        return $this->sequenceNo;
    }
}
