<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Header\Frame;

final class FrameDecoder
{
    /**
     * @param string $data
     * @param int $offset
     * @return Frame
     * @throws InsufficientDataException
     */
    public function decodeFrame(string $data, int $offset = 0): Frame
    {
        if ((\strlen($data) - $offset) < Frame::WIRE_SIZE) {
            throw new InsufficientDataException(
                "Frame requires " . Frame::WIRE_SIZE . " bytes, got " . (\strlen($data) - $offset) . " bytes"
            );
        }

        [
            'size' => $size,
            'protocol' => $protocol,
            'source' => $source,
        ] = \unpack('vsize/vprotocol/Vsource', $data, $offset);

        $origin        =       ($protocol & 0xC000) >> 14;
        $isTagged      = (bool)($protocol & 0x2000);
        $isAddressable = (bool)($protocol & 0x1000);
        $protocol      =       ($protocol & 0x0FFF);

        return new Frame($size, $origin, $isTagged, $isAddressable, $protocol, $source);
    }
}
