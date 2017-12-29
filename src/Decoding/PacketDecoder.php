<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Packet;

final class PacketDecoder
{
    private const HEADER_OFFSET = 0;
    private const MESSAGE_OFFSET = Header::WIRE_SIZE;

    private $headerDecoder;
    private $messageDecoder;

    public function __construct(HeaderDecoder $headerDecoder = null, MessageDecoder $messageDecoder = null)
    {
        $this->headerDecoder = $headerDecoder ?? new HeaderDecoder;
        $this->messageDecoder = $messageDecoder ?? new MessageDecoder;
    }

    /**
     * @param string $buffer
     * @return Packet
     * @throws DecodingException
     */
    public function decodePacket(string $buffer): Packet
    {
        $dataLength = \strlen($buffer);

        if ($dataLength < Header::WIRE_SIZE) {
            throw new InsufficientDataException(
                "Data length {$dataLength} less than minimum packet size " . Header::WIRE_SIZE
            );
        }

        $length = \unpack('vlength', $buffer)['length'];

        if ($length !== $dataLength) {
            throw new InsufficientDataException(
                "Packet length is stated to be {$length} bytes, buffer is {$dataLength} bytes"
            );
        }

        $header = $this->headerDecoder->decodeHeader(\substr($buffer, self::HEADER_OFFSET, Header::WIRE_SIZE));
        $payload = $this->messageDecoder->decodeMessage($header->getProtocolHeader()->getType(), \substr($buffer, self::MESSAGE_OFFSET));

        return new Packet($header, $payload);
    }
}
