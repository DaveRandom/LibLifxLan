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

    public static function createFromMessage(Message $message, int $sourceId, ?MacAddress $destination, int $sequenceNo, int $responsePattern): Packet
    {
        $frame = new Frame(
            Header::WIRE_SIZE + $message->getWireSize(),
            self::MESSAGE_ORIGIN,
            /* tagged */ $destination === null,
            /* addressable */ true,
            self::PROTOCOL_NUMBER,
            $sourceId
        );

        $destination = $destination ?? new MacAddress(0, 0, 0, 0, 0, 0);
        $isAckRequired = (bool)($responsePattern & ResponsePattern::REQUIRE_ACK);
        $isResponseRequired = (bool)($responsePattern & ResponsePattern::REQUIRE_RESPONSE);

        $frameAddress = new FrameAddress($destination, $isAckRequired, $isResponseRequired, $sequenceNo);
        $protocolHeader = new ProtocolHeader($message->getTypeId());

        return new Packet(new Header($frame, $frameAddress, $protocolHeader), $message);
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
