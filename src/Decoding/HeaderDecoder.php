<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;

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

    public function getFrameDecoder(): FrameDecoder
    {
        return $this->frameDecoder;
    }

    public function getFrameAddressDecoder(): FrameAddressDecoder
    {
        return $this->frameAddressDecoder;
    }

    public function getProtocolHeaderDecoder(): ProtocolHeaderDecoder
    {
        return $this->protocolHeaderDecoder;
    }

    /**
     * @param string $data
     * @param int $offset
     * @return Header
     * @throws InsufficientDataException
     * @throws DecodingException
     */
    public function decodeHeader(string $data, int $offset = 0): Header
    {
        if ((\strlen($data) - $offset) < Header::WIRE_SIZE) {
            throw new InsufficientDataException(
                "Header requires " . Header::WIRE_SIZE . " bytes, got " . (\strlen($data) - $offset) . " bytes"
            );
        }

        $frame = $this->frameDecoder->decodeFrame($data, $offset + self::FRAME_OFFSET);
        $frameAddress = $this->frameAddressDecoder->decodeFrameAddress($data, $offset + self::FRAME_ADDRESS_OFFSET);
        $protocolHeader = $this->protocolHeaderDecoder->decodeProtocolHeader($data, $offset + self::PROTOCOL_HEADER_OFFSET);

        return new Header($frame, $frameAddress, $protocolHeader);
    }
}
