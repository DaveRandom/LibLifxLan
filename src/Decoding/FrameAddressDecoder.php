<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\Network\MacAddress;

final class FrameAddressDecoder
{
    /**
     * @param string $data
     * @param int $offset
     * @return FrameAddress
     * @throws InsufficientDataException
     */
    public function decodeFrameAddress(string $data, int $offset = 0): FrameAddress
    {
        if ((\strlen($data) - $offset) < FrameAddress::WIRE_SIZE) {
            throw new InsufficientDataException(
                "Frame address requires " . FrameAddress::WIRE_SIZE
                . " bytes, got " . (\strlen($data) - $offset) . " bytes"
            );
        }

        [
            'mac1' => $mac1,
            'mac2' => $mac2,
            'mac3' => $mac3,
            'mac4' => $mac4,
            'mac5' => $mac5,
            'mac6' => $mac6,
            'flags' => $flags,
            'sequence' => $sequence,
        ] = \unpack('C8mac/C6reserved/Cflags/Csequence', $data, $offset);

        $target = new MacAddress($mac1, $mac2, $mac3, $mac4, $mac5, $mac6);
        $isAckRequired      = (bool)($flags & 0x02);
        $isResponseRequired = (bool)($flags & 0x01);

        return new FrameAddress($target, $isAckRequired, $isResponseRequired, $sequence);
    }
}
