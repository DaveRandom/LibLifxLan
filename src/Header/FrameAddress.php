<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use DaveRandom\Network\MacAddress;
use function DaveRandom\LibLifxLan\validate_uint8;

final class FrameAddress
{
    public const WIRE_SIZE = 16;

    private $target;
    private $ackRequired;
    private $responseRequired;
    private $sequenceNo;

    /**
     * @param MacAddress $target
     * @param bool $ackRequired
     * @param bool $responseRequired
     * @param int $sequenceNo
     * @throws InvalidValueException
     */
    public function __construct(MacAddress $target, bool $ackRequired, bool $responseRequired, int $sequenceNo)
    {
        $this->target = $target;
        $this->ackRequired = $ackRequired;
        $this->responseRequired = $responseRequired;
        $this->sequenceNo = validate_uint8('Sequence number', $sequenceNo);
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
