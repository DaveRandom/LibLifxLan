<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;

final class ProtocolHeaderDecoder
{
    /**
     * @param string $data
     * @param int $offset
     * @return ProtocolHeader
     * @throws InsufficientDataException
     */
    public function decodeProtocolHeader(string $data, int $offset = 0): ProtocolHeader
    {
        if ((\strlen($data) - $offset) < ProtocolHeader::WIRE_SIZE) {
            throw new InsufficientDataException(
                "Protocol header requires " . ProtocolHeader::WIRE_SIZE
                . " bytes, got " . (\strlen($data) - $offset) . " bytes"
            );
        }

        return new ProtocolHeader(\unpack('Preserved/vtype/vreserved', $data, $offset)['type']);
    }
}
