<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\Encoding\HeaderEncoder;
use DaveRandom\LibLifxLan\Encoding\MessageEncoder;
use DaveRandom\LibLifxLan\Encoding\PacketEncoder;
use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetColor;
use DaveRandom\LibLifxLan\Packet;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class PacketEncoderTest extends TestCase
{
    public function testHeaderEncoderPropertyExplicitValue(): void
    {
        $headerEncoder = new HeaderEncoder();
        $packetEncoder = new PacketEncoder($headerEncoder, null);

        $this->assertSame($packetEncoder->getHeaderEncoder(), $headerEncoder);
    }

    public function testHeaderEncoderPropertyImplicitValue(): void
    {
        $packetEncoder = new PacketEncoder();

        $this->assertInstanceOf(HeaderEncoder::class, $packetEncoder->getHeaderEncoder());
    }

    public function testMessageEncoderPropertyExplicitValue(): void
    {
        $messageEncoder = new MessageEncoder();
        $packetEncoder = new PacketEncoder(null, $messageEncoder);

        $this->assertSame($packetEncoder->getMessageEncoder(), $messageEncoder);
    }

    public function testMessageEncoderPropertyImplicitValue(): void
    {
        $packetEncoder = new PacketEncoder();

        $this->assertInstanceOf(MessageEncoder::class, $packetEncoder->getMessageEncoder());
    }

    /**
     * @throws \DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException
     */
    public function testEncodeHeaderWithExampleData(): void
    {
        $packet = new Packet(
            new Header(
                new Frame(
                    ExampleWireData::FRAME_SIZE,
                    ExampleWireData::FRAME_ORIGIN,
                    ExampleWireData::FRAME_TAGGED_FLAG,
                    ExampleWireData::FRAME_ADDRESSABLE_FLAG,
                    ExampleWireData::FRAME_PROTOCOL_NUMBER,
                    ExampleWireData::FRAME_SOURCE
                ),
                new FrameAddress(
                    MacAddress::fromOctets(...ExampleWireData::FRAME_ADDRESS_TARGET_OCTETS),
                    ExampleWireData::FRAME_ADDRESS_ACK_FLAG,
                    ExampleWireData::FRAME_ADDRESS_RES_FLAG,
                    ExampleWireData::FRAME_ADDRESS_SEQUENCE_NO
                ),
                new ProtocolHeader(
                    ExampleWireData::PROTOCOL_HEADER_MESSAGE_TYPE_ID
                )
            ),
            new SetColor(
                new ColorTransition(
                    new HsbkColor(
                        ExampleWireData::PAYLOAD_HSBK_HUE,
                        ExampleWireData::PAYLOAD_HSBK_SATURATION,
                        ExampleWireData::PAYLOAD_HSBK_BRIGHTNESS,
                        ExampleWireData::PAYLOAD_HSBK_TEMPERATURE
                    ),
                    ExampleWireData::PAYLOAD_TRANSITION_TIME
                )
            )
        );

        $this->assertSame((new PacketEncoder)->encodePacket($packet), ExampleWireData::PACKET_DATA);
    }
}
