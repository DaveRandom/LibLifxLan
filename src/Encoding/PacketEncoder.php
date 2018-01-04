<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Encoding;

use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException;
use DaveRandom\LibLifxLan\Packet;

final class PacketEncoder
{
    private $headerEncoder;
    private $messageEncoder;

    public function __construct(HeaderEncoder $headerEncoder = null, MessageEncoder $messageEncoder = null)
    {
        $this->headerEncoder = $headerEncoder ?? new HeaderEncoder;
        $this->messageEncoder = $messageEncoder ?? new MessageEncoder();
    }

    public function getHeaderEncoder(): HeaderEncoder
    {
        return $this->headerEncoder;
    }

    public function getMessageEncoder(): MessageEncoder
    {
        return $this->messageEncoder;
    }

    /**
     * @param Packet $packet
     * @return string
     * @throws InvalidMessageHeaderException
     */
    public function encodePacket(Packet $packet): string
    {
        return $this->headerEncoder->encodeHeader($packet->getHeader())
            . $this->messageEncoder->encodeMessage($packet->getMessage());
    }
}
