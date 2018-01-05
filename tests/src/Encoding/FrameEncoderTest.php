<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\Encoding\FrameEncoder;
use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\LibLifxLan\Tests\WireData\FrameWireData;
use PHPUnit\Framework\TestCase;

final class FrameEncoderTest extends TestCase
{
    public function testEncodeFrameEncodesSizeCorrectly(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_SIZE_DATA as $expectedData => $size) {
            $frame = new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source);
            $this->assertSame($encoder->encodeFrame($frame), $expectedData);
        }
    }

    public function testEncodeFrameEncodesOriginCorrectly(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_ORIGIN_DATA as $expectedData => $origin) {
            $frame = new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source);
            $this->assertSame($encoder->encodeFrame($frame), $expectedData);
        }
    }

    public function testEncodeFrameEncodesFlagsCorrectly(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_FLAGS_DATA as $expectedData => ['tag' => $taggedFlag, 'add' => $addressableFlag]) {
            $frame = new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source);
            $this->assertSame($encoder->encodeFrame($frame), $expectedData);
        }
    }

    public function testEncodeFrameEncodesProtocolNoCorrectly(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_PROTOCOL_DATA as $expectedData => $protocolNo) {
            $frame = new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source);
            $this->assertSame($encoder->encodeFrame($frame), $expectedData);
        }
    }

    public function testEncodeFrameEncodesSourceCorrectly(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;

        foreach (FrameWireData::VALID_SOURCE_DATA as $expectedData => $source) {
            $frame = new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source);
            $this->assertSame($encoder->encodeFrame($frame), $expectedData);
        }
    }

    public function testEncodeFrameDoesNotThrowWhenOriginIs0AndAllowOriginVarianceExplicitlyDisabled(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => false,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = 0;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        $this->assertSame(
            $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source)),
            "\x00\x00\x00\x00\x00\x00\x00\x00"
        );
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException
     */
    public function testEncodeFrameThrowsWhenOriginIsNot0AndAllowOriginVarianceExplicitlyDisabled(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => false,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = 1;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source));
    }

    public function testEncodeFrameDoesNotThrowWhenOriginIs0AndAllowOriginVarianceDefault(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = 0;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        $this->assertSame(
            $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source)),
            "\x00\x00\x00\x00\x00\x00\x00\x00"
        );
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException
     */
    public function testEncodeFrameThrowsWhenOriginIsNot0AndAllowOriginVarianceDefault(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = 1;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source));
    }

    public function testEncodeFrameDoesNotThrowWhenProtocolNoIs1024AndAllowProtocolNoVarianceExplicitlyDisabled(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => false,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = 1024;
        $source = FrameWireData::DEFAULT_SOURCE;

        $this->assertSame(
            $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source)),
            "\x00\x00\x00\x04\x00\x00\x00\x00"
        );
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException
     */
    public function testEncodeFrameThrowsWhenProtocolNoIsNot1024AndAllowProtocolNoVarianceExplicitlyDisabled(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
            FrameEncoder::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => false,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = 1023;
        $source = FrameWireData::DEFAULT_SOURCE;

        $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source));
    }

    public function testEncodeFrameDoesNotThrowWhenProtocolNoIs1024AndAllowProtocolNoVarianceDefault(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = 1024;
        $source = FrameWireData::DEFAULT_SOURCE;

        $this->assertSame(
            $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source)),
            "\x00\x00\x00\x04\x00\x00\x00\x00"
        );
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException
     */
    public function testEncodeFrameThrowsWhenProtocolNoIsNot1024AndAllowProtocolNoVarianceDefault(): void
    {
        $encoder = new FrameEncoder([
            FrameEncoder::OP_ALLOW_ORIGIN_VARIANCE => true,
        ]);

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = 1023;
        $source = FrameWireData::DEFAULT_SOURCE;

        $encoder->encodeFrame(new Frame($size, $origin, $taggedFlag, $addressableFlag, $protocolNo, $source));
    }

    public function testEncodeFrameWithExampleData(): void
    {
        $frame = new Frame(
            ExampleWireData::FRAME_SIZE,
            ExampleWireData::FRAME_ORIGIN,
            ExampleWireData::FRAME_TAGGED_FLAG,
            ExampleWireData::FRAME_ADDRESSABLE_FLAG,
            ExampleWireData::FRAME_PROTOCOL_NUMBER,
            ExampleWireData::FRAME_SOURCE
        );

        $this->assertSame((new FrameEncoder)->encodeFrame($frame), ExampleWireData::FRAME_DATA);
    }
}
