<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;

final class HeaderDecoder
{
    private const FRAME_OFFSET = 0;
    private const FRAME_ADDRESS_OFFSET = self::FRAME_OFFSET + Frame::WIRE_SIZE;
    private const PROTOCOL_HEADER_OFFSET = self::FRAME_ADDRESS_OFFSET + FrameAddress::WIRE_SIZE;

    private $frameDecoder;
    private $frameAddressDecoder;
    private $protocolHeaderDecoder;

    public function __construct(FrameDecoder $frameDecoder = null, FrameAddressDecoder $frameAddressDecoder = null, ProtocolHeaderDecoder $protocolHeaderDecoder = null)
    {
        $this->frameDecoder = $frameDecoder ?? new FrameDecoder;
        $this->frameAddressDecoder = $frameAddressDecoder ?? new FrameAddressDecoder;
        $this->protocolHeaderDecoder = $protocolHeaderDecoder ?? new ProtocolHeaderDecoder;
    }

    public function decodeHeader(string $data): Header
    {
        \assert(
            \strlen($data) === Header::WIRE_SIZE,
            new \Error("Header data length expected to be " . Header::WIRE_SIZE . " bytes, got " . \strlen($data) . " bytes")
        );

        $frame = $this->frameDecoder->decodeFrame(\substr($data, self::FRAME_OFFSET, Frame::WIRE_SIZE));
        $frameAddress = $this->frameAddressDecoder->decodeFrameAddress(\substr($data, self::FRAME_ADDRESS_OFFSET, FrameAddress::WIRE_SIZE));
        $protocolHeader = $this->protocolHeaderDecoder->decodeProtocolHeader(\substr($data, self::PROTOCOL_HEADER_OFFSET, ProtocolHeader::WIRE_SIZE));

        return new Header($frame, $frameAddress, $protocolHeader);
    }
}
