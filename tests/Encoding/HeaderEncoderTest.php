<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\Encoding\FrameAddressEncoder;
use DaveRandom\LibLifxLan\Encoding\FrameEncoder;
use DaveRandom\LibLifxLan\Encoding\HeaderEncoder;
use DaveRandom\LibLifxLan\Encoding\ProtocolHeaderEncoder;
use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class HeaderEncoderTest extends TestCase
{
    public function testFrameEncoderPropertyExplicitValue(): void
    {
        $frameEncoder = new FrameEncoder();
        $headerEncoder = new HeaderEncoder($frameEncoder, null, null);

        $this->assertSame($headerEncoder->getFrameEncoder(), $frameEncoder);
    }

    public function testFrameEncoderPropertyImplicitValue(): void
    {
        $headerEncoder = new HeaderEncoder();

        $this->assertInstanceOf(FrameEncoder::class, $headerEncoder->getFrameEncoder());
    }

    public function testFrameAddressEncoderPropertyExplicitValue(): void
    {
        $frameAddressEncoder = new FrameAddressEncoder();
        $headerEncoder = new HeaderEncoder(null, $frameAddressEncoder, null);

        $this->assertSame($headerEncoder->getFrameAddressEncoder(), $frameAddressEncoder);
    }

    public function testFrameAddressEncoderPropertyImplicitValue(): void
    {
        $headerEncoder = new HeaderEncoder();

        $this->assertInstanceOf(FrameAddressEncoder::class, $headerEncoder->getFrameAddressEncoder());
    }

    public function testProtocolHeaderEncoderPropertyExplicitValue(): void
    {
        $protocolHeaderEncoder = new ProtocolHeaderEncoder();
        $headerEncoder = new HeaderEncoder(null, null, $protocolHeaderEncoder);

        $this->assertSame($headerEncoder->getProtocolHeaderEncoder(), $protocolHeaderEncoder);
    }

    public function testProtocolHeaderEncoderPropertyImplicitValue(): void
    {
        $headerEncoder = new HeaderEncoder();

        $this->assertInstanceOf(ProtocolHeaderEncoder::class, $headerEncoder->getProtocolHeaderEncoder());
    }

    /**
     * @throws \DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException
     */
    public function testEncodeHeaderWithExampleData(): void
    {
        $header = new Header(
            new Frame(
                ExampleWireData::FRAME_SIZE,
                ExampleWireData::FRAME_ORIGIN,
                ExampleWireData::FRAME_TAGGED_FLAG,
                ExampleWireData::FRAME_ADDRESSABLE_FLAG,
                ExampleWireData::FRAME_PROTOCOL_NUMBER,
                ExampleWireData::FRAME_SOURCE
            ),
            new FrameAddress(
                new MacAddress(...ExampleWireData::FRAME_ADDRESS_TARGET_OCTETS),
                ExampleWireData::FRAME_ADDRESS_ACK_FLAG,
                ExampleWireData::FRAME_ADDRESS_RES_FLAG,
                ExampleWireData::FRAME_ADDRESS_SEQUENCE_NO
            ),
            new ProtocolHeader(
                ExampleWireData::PROTOCOL_HEADER_MESSAGE_TYPE_ID
            )
        );

        $this->assertSame((new HeaderEncoder)->encodeHeader($header), ExampleWireData::HEADER_DATA);
    }
}
