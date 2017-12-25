<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Encoding;

use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;

final class ProtocolHeaderEncoder extends Encoder
{
    /**
     * @param ProtocolHeader $protocolHeader
     * @return string
     * @throws InvalidMessageHeaderException
     */
    public function encodeProtocolHeader(ProtocolHeader $protocolHeader): string
    {
        $type = $protocolHeader->getType();

        if ($type < 0 || $type > 65535) {
            throw new InvalidMessageHeaderException("Message type value {$type} outside allowable range 0 - 65535");
        }

        return "\x00\x00\x00\x00\x00\x00\x00\x00" . \pack('v', $type) . "\x00\x00";
    }
}
