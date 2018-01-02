<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

final class Header
{
    public const WIRE_SIZE = Frame::WIRE_SIZE + FrameAddress::WIRE_SIZE + ProtocolHeader::WIRE_SIZE;

    private $frame;
    private $frameAddress;
    private $protocolHeader;

    private function setFrame(Frame $frame): void
    {
        $this->frame = $frame;
    }

    private function setFrameAddress(FrameAddress $frameAddress): void
    {
        $this->frameAddress = $frameAddress;
    }

    private function setProtocolHeader(ProtocolHeader $protocolHeader): void
    {
        $this->protocolHeader = $protocolHeader;
    }

    public function __construct(Frame $frame, FrameAddress $frameAddress, ProtocolHeader $protocolHeader)
    {
        $this->setFrame($frame);
        $this->setFrameAddress($frameAddress);
        $this->setProtocolHeader($protocolHeader);
    }

    public function getFrame(): Frame
    {
        return $this->frame;
    }

    public function getFrameAddress(): FrameAddress
    {
        return $this->frameAddress;
    }

    public function getProtocolHeader(): ProtocolHeader
    {
        return $this->protocolHeader;
    }
}
