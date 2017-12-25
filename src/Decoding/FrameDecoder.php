<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Header\Frame;

final class FrameDecoder
{
    public function decodeFrame(string $data): Frame
    {
        \assert(
            \strlen($data) === Frame::WIRE_SIZE,
            new \Error("Frame data length expected to be " . Frame::WIRE_SIZE . " bytes, got " . \strlen($data) . " bytes")
        );

        $parts = \unpack('vsize/vprotocol/Vsource', $data);

        $size = $parts['size'];
        $source = $parts['source'];

        $origin        =       ($parts['protocol'] & 0xC000) >> 14;
        $isTagged      = (bool)($parts['protocol'] & 0x2000);
        $isAddressable = (bool)($parts['protocol'] & 0x1000);
        $protocol      =       ($parts['protocol'] & 0x0FFF);

        return new Frame($size, $origin, $isTagged, $isAddressable, $protocol, $source);
    }
}
