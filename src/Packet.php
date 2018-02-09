<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan;

use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\LibLifxLan\Messages\Message;
use DaveRandom\Network\MacAddress;

final class Packet
{
    public const MESSAGE_ORIGIN = 0;
    public const PROTOCOL_NUMBER = 1024;

    private $header;
    private $message;

    private static function createFrameFromMessage(Message $message, int $sourceId, ?MacAddress $destination): Frame
    {
        return new Frame(
            Header::WIRE_SIZE + $message->getWireSize(),
            self::MESSAGE_ORIGIN,
            /* tagged */ $destination === null,
            /* addressable */ true,
            self::PROTOCOL_NUMBER,
            $sourceId
        );
    }

    private static function createFrameAddress(?MacAddress $destination, int $responsePattern, int $sequenceNo): FrameAddress
    {
        return new FrameAddress(
            $destination ?? MacAddress::fromOctets(0, 0, 0, 0, 0, 0),
            (bool)($responsePattern & ResponsePattern::REQUIRE_ACK),
            (bool)($responsePattern & ResponsePattern::REQUIRE_RESPONSE),
            $sequenceNo
        );
    }

    public static function createFromMessage(
        Message $message,
        int $sourceId,
        ?MacAddress $destination,
        int $sequenceNo,
        int $responsePattern
    ): Packet
    {
        $header = new Header(
            self::createFrameFromMessage($message, $sourceId, $destination),
            self::createFrameAddress($destination, $responsePattern, $sequenceNo),
            new ProtocolHeader($message->getTypeId())
        );

        return new Packet($header, $message);
    }

    public function __construct(Header $header, Message $message)
    {
        $this->header = $header;
        $this->message = $message;
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
