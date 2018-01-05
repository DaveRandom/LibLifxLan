<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Decoding\HeaderDecoder;
use DaveRandom\LibLifxLan\Decoding\MessageDecoder;
use DaveRandom\LibLifxLan\Decoding\PacketDecoder;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetColor;
use DaveRandom\LibLifxLan\Tests\Decoding\OffsetTestValues;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class PacketDecoderTest extends TestCase
{
    public function testHeaderDecoderPropertyExplicitValue(): void
    {
        $headerDecoder = new HeaderDecoder();
        $packetDecoder = new PacketDecoder($headerDecoder, null);

        $this->assertSame($packetDecoder->getHeaderDecoder(), $headerDecoder);
    }

    public function testHeaderDecoderPropertyImplicitValue(): void
    {
        $packetDecoder = new PacketDecoder();

        $this->assertInstanceOf(HeaderDecoder::class, $packetDecoder->getHeaderDecoder());
    }

    public function testMessageDecoderPropertyExplicitValue(): void
    {
        $messageDecoder = new MessageDecoder();
        $packetDecoder = new PacketDecoder(null, $messageDecoder);

        $this->assertSame($packetDecoder->getMessageDecoder(), $messageDecoder);
    }

    public function testMessageDecoderPropertyImplicitValue(): void
    {
        $packetDecoder = new PacketDecoder();

        $this->assertInstanceOf(MessageDecoder::class, $packetDecoder->getMessageDecoder());
    }

    public function testDecodePacketWithExampleData(): void
    {
        $packet = (new PacketDecoder)->decodePacket(ExampleWireData::PACKET_DATA);

        $header = $packet->getHeader();
        /** @var SetColor $message */
        $message = $packet->getMessage();

        $this->assertSame($header->getFrame()->getSize(), ExampleWireData::FRAME_SIZE);
        $this->assertSame($header->getFrame()->getOrigin(), ExampleWireData::FRAME_ORIGIN);
        $this->assertSame($header->getFrame()->isTagged(), ExampleWireData::FRAME_TAGGED_FLAG);
        $this->assertSame($header->getFrame()->isAddressable(), ExampleWireData::FRAME_ADDRESSABLE_FLAG);
        $this->assertSame($header->getFrame()->getProtocolNo(), ExampleWireData::FRAME_PROTOCOL_NUMBER);
        $this->assertSame($header->getFrame()->getSource(), ExampleWireData::FRAME_SOURCE);

        $this->assertTrue($header->getFrameAddress()->getTarget()->equals(new MacAddress(...ExampleWireData::FRAME_ADDRESS_TARGET_OCTETS)));
        $this->assertSame($header->getFrameAddress()->isAckRequired(), ExampleWireData::FRAME_ADDRESS_ACK_FLAG);
        $this->assertSame($header->getFrameAddress()->isResponseRequired(), ExampleWireData::FRAME_ADDRESS_RES_FLAG);
        $this->assertSame($header->getFrameAddress()->getSequenceNo(), ExampleWireData::FRAME_ADDRESS_SEQUENCE_NO);

        $this->assertSame($header->getProtocolHeader()->getType(), ExampleWireData::PROTOCOL_HEADER_MESSAGE_TYPE_ID);

        $this->assertSame($message->getTypeId(), SetColor::MESSAGE_TYPE_ID);
        $this->assertSame($message->getWireSize(), SetColor::WIRE_SIZE);

        $this->assertSame($message->getColorTransition()->getColor()->getHue(), ExampleWireData::PAYLOAD_HSBK_HUE);
        $this->assertSame($message->getColorTransition()->getColor()->getSaturation(), ExampleWireData::PAYLOAD_HSBK_SATURATION);
        $this->assertSame($message->getColorTransition()->getColor()->getBrightness(), ExampleWireData::PAYLOAD_HSBK_BRIGHTNESS);
        $this->assertSame($message->getColorTransition()->getColor()->getTemperature(), ExampleWireData::PAYLOAD_HSBK_TEMPERATURE);

        $this->assertSame($message->getColorTransition()->getDuration(), ExampleWireData::PAYLOAD_TRANSITION_TIME);
    }

    public function testDecodePacketWithExampleDataWithOffset(): void
    {
        $decoder = new PacketDecoder;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            $packet = $decoder->decodePacket($padding . ExampleWireData::PACKET_DATA, $offset);

            $header = $packet->getHeader();
            /** @var SetColor $message */
            $message = $packet->getMessage();

            $this->assertSame($header->getFrame()->getSize(), ExampleWireData::FRAME_SIZE);
            $this->assertSame($header->getFrame()->getOrigin(), ExampleWireData::FRAME_ORIGIN);
            $this->assertSame($header->getFrame()->isTagged(), ExampleWireData::FRAME_TAGGED_FLAG);
            $this->assertSame($header->getFrame()->isAddressable(), ExampleWireData::FRAME_ADDRESSABLE_FLAG);
            $this->assertSame($header->getFrame()->getProtocolNo(), ExampleWireData::FRAME_PROTOCOL_NUMBER);
            $this->assertSame($header->getFrame()->getSource(), ExampleWireData::FRAME_SOURCE);

            $this->assertTrue($header->getFrameAddress()->getTarget()->equals(new MacAddress(...ExampleWireData::FRAME_ADDRESS_TARGET_OCTETS)));
            $this->assertSame($header->getFrameAddress()->isAckRequired(), ExampleWireData::FRAME_ADDRESS_ACK_FLAG);
            $this->assertSame($header->getFrameAddress()->isResponseRequired(), ExampleWireData::FRAME_ADDRESS_RES_FLAG);
            $this->assertSame($header->getFrameAddress()->getSequenceNo(), ExampleWireData::FRAME_ADDRESS_SEQUENCE_NO);

            $this->assertSame($header->getProtocolHeader()->getType(), ExampleWireData::PROTOCOL_HEADER_MESSAGE_TYPE_ID);

            $this->assertSame($message->getTypeId(), SetColor::MESSAGE_TYPE_ID);
            $this->assertSame($message->getWireSize(), SetColor::WIRE_SIZE);

            $this->assertSame($message->getColorTransition()->getColor()->getHue(), ExampleWireData::PAYLOAD_HSBK_HUE);
            $this->assertSame($message->getColorTransition()->getColor()->getSaturation(), ExampleWireData::PAYLOAD_HSBK_SATURATION);
            $this->assertSame($message->getColorTransition()->getColor()->getBrightness(), ExampleWireData::PAYLOAD_HSBK_BRIGHTNESS);
            $this->assertSame($message->getColorTransition()->getColor()->getTemperature(), ExampleWireData::PAYLOAD_HSBK_TEMPERATURE);

            $this->assertSame($message->getColorTransition()->getDuration(), ExampleWireData::PAYLOAD_TRANSITION_TIME);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException
     */
    public function testDecodePacketDataTooShort(): void
    {
        (new PacketDecoder)->decodePacket(\substr(ExampleWireData::HEADER_DATA, 0, -1));
    }

    public function testDecodePacketDataTooShortWithOffset(): void
    {
        $failures = 0;
        $decoder = new PacketDecoder();

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            try {
                $decoder->decodePacket($padding . \substr(ExampleWireData::HEADER_DATA, 0, -1), $offset);
            } catch (InsufficientDataException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count(OffsetTestValues::OFFSETS));
    }
}
