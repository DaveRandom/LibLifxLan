<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\Header\ProtocolHeader;

final class ProtocolHeaderDecoder
{
    public function decodeProtocolHeader(string $data): ProtocolHeader
    {
        \assert(
            \strlen($data) === ProtocolHeader::WIRE_SIZE,
            new \Error("Protocol header data length expected to be " . ProtocolHeader::WIRE_SIZE . " bytes, got " . \strlen($data) . " bytes")
        );

        return new ProtocolHeader(\unpack('Preserved/vtype/vreserved', $data)['type']);
    }
}
