<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\LibLifxLan\Messages\Device\Requests\GetService;
use DaveRandom\LibLifxLan\Messages\Message;
use DaveRandom\LibLifxLan\Packet;
use DaveRandom\LibLifxLan\ResponsePattern;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class PacketTest extends TestCase
{
    public function testCreateFromMessageSetsWireSize(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertSame($packet->getHeader()->getFrame()->getSize(), Header::WIRE_SIZE + GetService::WIRE_SIZE);
    }

    public function testCreateFromMessageSetsOrigin(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertSame($packet->getHeader()->getFrame()->getOrigin(), Packet::MESSAGE_ORIGIN);
    }

    public function testCreateFromMessageWithDestination(): void
    {
        $address = new MacAddress(1, 2, 3, 4, 5, 6);

        $packet = Packet::createFromMessage(new GetService, 0, $address, 0, 0);

        $this->assertFalse($packet->getHeader()->getFrame()->isTagged());
        $this->assertTrue($packet->getHeader()->getFrameAddress()->getTarget()->equals($address));
    }

    public function testCreateFromMessageWithoutDestination(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);

        $this->assertTrue($packet->getHeader()->getFrame()->isTagged());
        $this->assertTrue($packet->getHeader()->getFrameAddress()->getTarget()->equals(new MacAddress(0, 0, 0, 0, 0, 0)));
    }

    public function testCreateFromMessageIsAddressable(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertTrue($packet->getHeader()->getFrame()->isAddressable());
    }

    public function testCreateFromMessageSetsSourceId(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertSame($packet->getHeader()->getFrame()->getSource(), 0);

        $packet = Packet::createFromMessage(new GetService, 12345, null, 0, 0);
        $this->assertSame($packet->getHeader()->getFrame()->getSource(), 12345);
    }

    public function testCreateFromMessageSetsProtocolNumber(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertSame($packet->getHeader()->getFrame()->getProtocolNo(), Packet::PROTOCOL_NUMBER);
    }

    public function testCreateFromMessageSetsSequenceNumber(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertSame($packet->getHeader()->getFrameAddress()->getSequenceNo(), 0);

        $packet = Packet::createFromMessage(new GetService, 0, null, 123, 0);
        $this->assertSame($packet->getHeader()->getFrameAddress()->getSequenceNo(), 123);
    }

    public function testCreateFromMessageResponsePattern(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertFalse($packet->getHeader()->getFrameAddress()->isAckRequired());
        $this->assertFalse($packet->getHeader()->getFrameAddress()->isResponseRequired());

        $packet = Packet::createFromMessage(new GetService, 0, null, 0, ResponsePattern::REQUIRE_ACK);
        $this->assertTrue($packet->getHeader()->getFrameAddress()->isAckRequired());
        $this->assertFalse($packet->getHeader()->getFrameAddress()->isResponseRequired());

        $packet = Packet::createFromMessage(new GetService, 0, null, 0, ResponsePattern::REQUIRE_RESPONSE);
        $this->assertFalse($packet->getHeader()->getFrameAddress()->isAckRequired());
        $this->assertTrue($packet->getHeader()->getFrameAddress()->isResponseRequired());

        $packet = Packet::createFromMessage(new GetService, 0, null, 0, ResponsePattern::REQUIRE_ACK | ResponsePattern::REQUIRE_RESPONSE);
        $this->assertTrue($packet->getHeader()->getFrameAddress()->isAckRequired());
        $this->assertTrue($packet->getHeader()->getFrameAddress()->isResponseRequired());
    }

    public function testCreateFromMessageSetsMessageType(): void
    {
        $packet = Packet::createFromMessage(new GetService, 0, null, 0, 0);
        $this->assertSame($packet->getHeader()->getProtocolHeader()->getType(), GetService::MESSAGE_TYPE_ID);
    }

    private function createHeader(): Header
    {
        return new Header(
            new Frame(0, 0, false, false, 0, 0),
            new FrameAddress(new MacAddress(1, 2, 3, 4, 5, 6), false, false, 0),
            new ProtocolHeader(0)
        );
    }

    private function createMessage(): Message
    {
        return new GetService;
    }

    public function testHeaderProperty(): void
    {
        $header = $this->createHeader();
        $message = $this->createMessage();
        $packet = new Packet($header, $message);

        $this->assertSame($packet->getHeader(), $header);
    }

    public function testMessageProperty(): void
    {
        $header = $this->createHeader();
        $message = $this->createMessage();
        $packet = new Packet($header, $message);

        $this->assertSame($packet->getMessage(), $message);
    }
}
