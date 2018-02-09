<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Encoding;

use DaveRandom\LibLifxLan\Header\FrameAddress;

final class FrameAddressEncoder
{
    private function encodeTarget(FrameAddress $frameAddress): string
    {
        return "{$frameAddress->getTarget()->toBinary()}\x00\x00";
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
     */
    private function encodeSequenceNo(FrameAddress $frameAddress): string
    {
        return \chr($frameAddress->getSequenceNo());
    }

    /**
     * @param FrameAddress $frameAddress
     * @return string
     */
    public function encodeFrameAddress(FrameAddress $frameAddress): string
    {
        return $this->encodeTarget($frameAddress)
            . $this->encodeFlags($frameAddress)
            . $this->encodeSequenceNo($frameAddress);
    }
}
