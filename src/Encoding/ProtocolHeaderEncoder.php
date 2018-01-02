<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Encoding;

use DaveRandom\LibLifxLan\Header\ProtocolHeader;

final class ProtocolHeaderEncoder
{
    /**
     * @param ProtocolHeader $protocolHeader
     * @return string
     */
    public function encodeProtocolHeader(ProtocolHeader $protocolHeader): string
    {
        return "\x00\x00\x00\x00\x00\x00\x00\x00" . \pack('v', $protocolHeader->getType()) . "\x00\x00";
    }
}
