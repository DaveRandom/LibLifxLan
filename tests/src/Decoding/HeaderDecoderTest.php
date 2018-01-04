<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Decoding\FrameAddressDecoder;
use DaveRandom\LibLifxLan\Decoding\FrameDecoder;
use DaveRandom\LibLifxLan\Decoding\HeaderDecoder;
use DaveRandom\LibLifxLan\Decoding\ProtocolHeaderDecoder;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class HeaderDecoderTest extends TestCase
{
    public function testFrameDecoderPropertyExplicitValue(): void
    {
        $frameDecoder = new FrameDecoder();
        $headerDecoder = new HeaderDecoder($frameDecoder, null, null);

        $this->assertSame($headerDecoder->getFrameDecoder(), $frameDecoder);
    }

    public function testFrameDecoderPropertyImplicitValue(): void
    {
        $headerDecoder = new HeaderDecoder();

        $this->assertInstanceOf(FrameDecoder::class, $headerDecoder->getFrameDecoder());
    }

    public function testFrameAddressDecoderPropertyExplicitValue(): void
    {
        $frameAddressDecoder = new FrameAddressDecoder();
        $headerDecoder = new HeaderDecoder(null, $frameAddressDecoder, null);

        $this->assertSame($headerDecoder->getFrameAddressDecoder(), $frameAddressDecoder);
    }

    public function testFrameAddressDecoderPropertyImplicitValue(): void
    {
        $headerDecoder = new HeaderDecoder();

        $this->assertInstanceOf(FrameAddressDecoder::class, $headerDecoder->getFrameAddressDecoder());
    }

    public function testProtocolHeaderDecoderPropertyExplicitValue(): void
    {
        $protocolHeaderDecoder = new ProtocolHeaderDecoder();
        $headerDecoder = new HeaderDecoder(null, null, $protocolHeaderDecoder);

        $this->assertSame($headerDecoder->getProtocolHeaderDecoder(), $protocolHeaderDecoder);
    }

    public function testProtocolHeaderDecoderPropertyImplicitValue(): void
    {
        $headerDecoder = new HeaderDecoder();

        $this->assertInstanceOf(ProtocolHeaderDecoder::class, $headerDecoder->getProtocolHeaderDecoder());
    }

    public function testDecodeHeaderWithExampleData(): void
    {
        $header = (new HeaderDecoder)->decodeHeader(ExampleWireData::HEADER_DATA);

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
    }

    public function testDecodeHeaderWithExampleDataWithOffset(): void
    {
        $decoder = new HeaderDecoder;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            $header = $decoder->decodeHeader($padding . ExampleWireData::HEADER_DATA, $offset);

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
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException
     */
    public function testDecodeHeaderDataTooShort(): void
    {
        (new HeaderDecoder)->decodeHeader(\substr(ExampleWireData::HEADER_DATA, 0, -1));
    }

    public function testDecodeHeaderDataTooShortWithOffset(): void
    {
        $failures = 0;
        $decoder = new HeaderDecoder();

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            try {
                $decoder->decodeHeader($padding . \substr(ExampleWireData::HEADER_DATA, 0, -1), $offset);
            } catch (InsufficientDataException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count(OffsetTestValues::OFFSETS));
    }
}
