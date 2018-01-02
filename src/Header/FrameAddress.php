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

    private function setTarget(MacAddress $target): void
    {
        $this->target = $target;
    }

    private function setAckRequired(bool $ackRequired): void
    {
        $this->ackRequired = $ackRequired;
    }

    private function setResponseRequired(bool $responseRequired): void
    {
        $this->responseRequired = $responseRequired;
    }

    /**
     * @param int $sequenceNo
     * @throws InvalidValueException
     */
    private function setSequenceNo(int $sequenceNo): void
    {
        if ($sequenceNo < 0 || $sequenceNo > 255) {
            throw new InvalidValueException("Sequence number value {$sequenceNo} outside allowable range 0 - 255");
        }

        $this->sequenceNo = $sequenceNo;
    }

    /**
     * @param MacAddress $target
     * @param bool $ackRequired
     * @param bool $responseRequired
     * @param int $sequenceNo
     * @throws InvalidValueException
     */
    public function __construct(MacAddress $target, bool $ackRequired, bool $responseRequired, int $sequenceNo)
    {
        $this->setTarget($target);
        $this->setAckRequired($ackRequired);
        $this->setResponseRequired($responseRequired);
        $this->setSequenceNo($sequenceNo);
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
