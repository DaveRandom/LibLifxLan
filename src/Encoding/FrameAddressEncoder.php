<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Encoding;

use DaveRandom\LifxLan\Encoding\Exceptions\InvalidMessageHeaderException;
use DaveRandom\LifxLan\Header\FrameAddress;

final class FrameAddressEncoder extends Encoder
{
    /**
     * If enabled, do not check the value of the sequence number but automatically truncate it to 8 bits instead
     */
    public const OP_SEQUENCE_AUTO_WRAP = 0b1;

    private function encodeTarget(FrameAddress $frameAddress): string
    {
        $target = $frameAddress->getTarget();

        return \pack(
            'C6',
            $target->getOctet1(),
            $target->getOctet2(),
            $target->getOctet3(),
            $target->getOctet4(),
            $target->getOctet5(),
            $target->getOctet6()
        ) . "\x00\x00";
    }

    private function encodeFlags(FrameAddress $frameAddress): string
    {
        $ackRequired = ((int)$frameAddress->isAckRequired()) << 1;
        $responseRequired = (int)$frameAddress->isResponseRequired();

        return "\x00\x00\x00\x00\x00\x00" . \chr($ackRequired | $responseRequired);
    }

    /**
     * @param FrameAddress $frameAddress
     * @return string
     * @throws InvalidMessageHeaderException
     */
    private function encodeSequenceNo(FrameAddress $frameAddress): string
    {
        $sequenceNo = $frameAddress->getSequenceNo();

        if (!$this->options[self::OP_SEQUENCE_AUTO_WRAP] && ($sequenceNo < 0 || $sequenceNo > 255)) {
            throw new InvalidMessageHeaderException("Sequence number value {$sequenceNo} outside allowable range 0 - 255");
        }

        return \chr($sequenceNo & 0xff);
    }

    /**
     * @param FrameAddress $frameAddress
     * @return string
     * @throws InvalidMessageHeaderException
     */
    public function encodeFrameAddress(FrameAddress $frameAddress): string
    {
        return $this->encodeTarget($frameAddress)
            . $this->encodeFlags($frameAddress)
            . $this->encodeSequenceNo($frameAddress);
    }

    public function __construct(array $options = [])
    {
        parent::__construct([
            self::OP_SEQUENCE_AUTO_WRAP => true,
        ]);
    }
}
