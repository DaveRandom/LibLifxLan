<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\Header\FrameAddress;
use DaveRandom\LifxLan\Network\MacAddress;

final class FrameAddressDecoder
{
    public function decodeFrameAddress(string $data): FrameAddress
    {
        \assert(
            \strlen($data) === FrameAddress::WIRE_SIZE,
            new \Error("Frame address data length expected to be " . FrameAddress::WIRE_SIZE . " bytes, got " . \strlen($data) . " bytes")
        );

        $parts = \unpack('C8tgt/C6reserved/Cflags/Csequence', $data);

        $target = new MacAddress($parts['tgt1'], $parts['tgt2'], $parts['tgt3'], $parts['tgt4'], $parts['tgt5'], $parts['tgt6']);

        $isAckRequired      = (bool)($parts['flags'] & 0x02);
        $isResponseRequired = (bool)($parts['flags'] & 0x01);

        $sequence = $parts['sequence'];

        return new FrameAddress($target, $isAckRequired, $isResponseRequired, $sequence);
    }
}
