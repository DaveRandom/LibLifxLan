<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use DaveRandom\Network\MacAddress;

final class FrameAddress
{
    public const WIRE_SIZE = 16;

    private $target;
    private $ackRequired;
    private $responseRequired;
    private $sequenceNo;

    /**
     * FrameAddress constructor.
     * @param MacAddress $target
     * @param bool $ackRequired
     * @param bool $responseRequired
     * @param int $sequenceNo
     * @throws InvalidValueException
     */
    public function __construct(MacAddress $target, bool $ackRequired, bool $responseRequired, int $sequenceNo)
    {
        if ($sequenceNo < 0 || $sequenceNo > 255) {
            throw new InvalidValueException("Sequence number value {$sequenceNo} outside allowable range 0 - 255");
        }

        $this->target = $target;
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
