<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Encoding;

use DaveRandom\LifxLan\Exceptions\InvalidMessageHeaderException;
use DaveRandom\LifxLan\Header\Header;

final class HeaderEncoder
{
    private $frameEncoder;
    private $frameAddressEncoder;
    private $protocolHeaderEncoder;

    public function __construct(
        FrameEncoder $frameEncoder = null,
        FrameAddressEncoder $frameAddressEncoder = null,
        ProtocolHeaderEncoder $protocolHeaderEncoder = null
    ) {
        $this->frameEncoder = $frameEncoder ?? new FrameEncoder;
        $this->frameAddressEncoder = $frameAddressEncoder ?? new FrameAddressEncoder;
        $this->protocolHeaderEncoder = $protocolHeaderEncoder ?? new ProtocolHeaderEncoder;
    }

    public function getFrameEncoder(): FrameEncoder
    {
        return $this->frameEncoder;
    }

    public function getFrameAddressEncoder(): FrameAddressEncoder
    {
        return $this->frameAddressEncoder;
    }

    public function getProtocolHeaderEncoder(): ProtocolHeaderEncoder
    {
        return $this->protocolHeaderEncoder;
    }

    /**
     * @param Header $header
     * @return string
     * @throws InvalidMessageHeaderException
     */
    public function encodeHeader(Header $header): string
    {
        return $this->frameEncoder->encodeFrame($header->getFrame())
            . $this->frameAddressEncoder->encodeFrameAddress($header->getFrameAddress())
            . $this->protocolHeaderEncoder->encodeProtocolHeader($header->getProtocolHeader());
    }
}
