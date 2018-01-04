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

    public function getHeaderDecoder(): HeaderDecoder
    {
        return $this->headerDecoder;
    }

    public function getMessageDecoder(): MessageDecoder
    {
        return $this->messageDecoder;
    }

    /**
     * @param string $buffer
     * @param int $offset
     * @param int|null $length
     * @return Packet
     * @throws DecodingException
     * @throws InsufficientDataException
     */
    public function decodePacket(string $buffer, int $offset = 0, int $length = null): Packet
    {
        $dataLength = $length ?? (\strlen($buffer) - $offset);

        if ($dataLength < Header::WIRE_SIZE) {
            throw new InsufficientDataException(
                "Data length {$dataLength} less than minimum packet size " . Header::WIRE_SIZE
            );
        }

        $statedLength = \unpack('vlength', $buffer, $offset)['length'];

        if ($statedLength !== $dataLength) {
            throw new InsufficientDataException(
                "Packet length is stated to be {$statedLength} bytes, buffer is {$dataLength} bytes"
            );
        }

        $header = $this->headerDecoder->decodeHeader($buffer, $offset + self::HEADER_OFFSET);
        $payload = $this->messageDecoder->decodeMessage(
            $header->getProtocolHeader()->getType(),
            $buffer,
            $offset + self::MESSAGE_OFFSET,
            $dataLength - Header::WIRE_SIZE
        );

        return new Packet($header, $payload);
    }
}
