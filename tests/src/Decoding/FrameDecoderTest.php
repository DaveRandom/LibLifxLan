<?php

namespace DaveRandom\LibLifxLan\Tests\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Decoding\FrameDecoder;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\LibLifxLan\Tests\WireData\FrameWireData;
use PHPUnit\Framework\TestCase;

class FrameDecoderTest extends TestCase
{
    public function testDecodeFrameDecodesSizeCorrectly(): void
    {
        $decoder = new FrameDecoder;

        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_SIZE_DATA as $data => $size) {
            $frame = $decoder->decodeFrame($data);
            $this->assertSame($frame->getSize(), $size);
            $this->assertSame($frame->getOrigin(), $origin);
            $this->assertSame($frame->isTagged(), $taggedFlag);
            $this->assertSame($frame->isAddressable(), $addressableFlag);
            $this->assertSame($frame->getProtocolNo(), $protocolNo);
            $this->assertSame($frame->getSource(), $source);
        }
    }

    public function testDecodeFrameDecodesSizeCorrectlyWithOffset(): void
    {
        $decoder = new FrameDecoder;

        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameWireData::VALID_SIZE_DATA as $data => $size) {
                $frame = $decoder->decodeFrame($padding . $data, $offset);
                $this->assertSame($frame->getSize(), $size);
                $this->assertSame($frame->getOrigin(), $origin);
                $this->assertSame($frame->isTagged(), $taggedFlag);
                $this->assertSame($frame->isAddressable(), $addressableFlag);
                $this->assertSame($frame->getProtocolNo(), $protocolNo);
                $this->assertSame($frame->getSource(), $source);
            }
        }
    }

    public function testDecodeFrameDecodesOriginCorrectly(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_ORIGIN_DATA as $data => $origin) {
            $frame = $decoder->decodeFrame($data);
            $this->assertSame($frame->getSize(), $size);
            $this->assertSame($frame->getOrigin(), $origin);
            $this->assertSame($frame->isTagged(), $taggedFlag);
            $this->assertSame($frame->isAddressable(), $addressableFlag);
            $this->assertSame($frame->getProtocolNo(), $protocolNo);
            $this->assertSame($frame->getSource(), $source);
        }
    }

    public function testDecodeFrameDecodesOriginCorrectlyWithOffset(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameWireData::VALID_ORIGIN_DATA as $data => $origin) {
                $frame = $decoder->decodeFrame($padding . $data, $offset);
                $this->assertSame($frame->getSize(), $size);
                $this->assertSame($frame->getOrigin(), $origin);
                $this->assertSame($frame->isTagged(), $taggedFlag);
                $this->assertSame($frame->isAddressable(), $addressableFlag);
                $this->assertSame($frame->getProtocolNo(), $protocolNo);
                $this->assertSame($frame->getSource(), $source);
            }
        }
    }

    public function testDecodeFrameDecodesFlagsCorrectly(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_FLAGS_DATA as $data => ['tag' => $taggedFlag, 'add' => $addressableFlag]) {
            $frame = $decoder->decodeFrame($data);
            $this->assertSame($frame->getSize(), $size);
            $this->assertSame($frame->getOrigin(), $origin);
            $this->assertSame($frame->isTagged(), $taggedFlag);
            $this->assertSame($frame->isAddressable(), $addressableFlag);
            $this->assertSame($frame->getProtocolNo(), $protocolNo);
            $this->assertSame($frame->getSource(), $source);
        }
    }

    public function testDecodeFrameDecodesFlagsCorrectlyWithOffset(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameWireData::VALID_FLAGS_DATA as $data => ['tag' => $taggedFlag, 'add' => $addressableFlag]) {
                $frame = $decoder->decodeFrame($padding . $data, $offset);
                $this->assertSame($frame->getSize(), $size);
                $this->assertSame($frame->getOrigin(), $origin);
                $this->assertSame($frame->isTagged(), $taggedFlag);
                $this->assertSame($frame->isAddressable(), $addressableFlag);
                $this->assertSame($frame->getProtocolNo(), $protocolNo);
                $this->assertSame($frame->getSource(), $source);
            }
        }
    }

    public function testDecodeFrameDecodesProtocolNoCorrectly(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (FrameWireData::VALID_PROTOCOL_DATA as $data => $protocolNo) {
            $frame = $decoder->decodeFrame($data);
            $this->assertSame($frame->getSize(), $size);
            $this->assertSame($frame->getOrigin(), $origin);
            $this->assertSame($frame->isTagged(), $taggedFlag);
            $this->assertSame($frame->isAddressable(), $addressableFlag);
            $this->assertSame($frame->getProtocolNo(), $protocolNo);
            $this->assertSame($frame->getSource(), $source);
        }
    }

    public function testDecodeFrameDecodesProtocolNoCorrectlyWithOffset(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $source = FrameWireData::DEFAULT_SOURCE;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameWireData::VALID_PROTOCOL_DATA as $data => $protocolNo) {
                $frame = $decoder->decodeFrame($padding . $data, $offset);
                $this->assertSame($frame->getSize(), $size);
                $this->assertSame($frame->getOrigin(), $origin);
                $this->assertSame($frame->isTagged(), $taggedFlag);
                $this->assertSame($frame->isAddressable(), $addressableFlag);
                $this->assertSame($frame->getProtocolNo(), $protocolNo);
                $this->assertSame($frame->getSource(), $source);
            }
        }
    }

    public function testDecodeFrameDecodesSourceCorrectly(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;

        foreach (FrameWireData::VALID_SOURCE_DATA as $data => $source) {
            $frame = $decoder->decodeFrame($data);
            $this->assertSame($frame->getSize(), $size);
            $this->assertSame($frame->getOrigin(), $origin);
            $this->assertSame($frame->isTagged(), $taggedFlag);
            $this->assertSame($frame->isAddressable(), $addressableFlag);
            $this->assertSame($frame->getProtocolNo(), $protocolNo);
            $this->assertSame($frame->getSource(), $source);
        }
    }

    public function testDecodeFrameDecodesSourceCorrectlyWithOffset(): void
    {
        $decoder = new FrameDecoder;

        $size = FrameWireData::DEFAULT_SIZE;
        $origin = FrameWireData::DEFAULT_ORIGIN;
        $taggedFlag = FrameWireData::DEFAULT_TAGGED_FLAG;
        $addressableFlag = FrameWireData::DEFAULT_ADDRESSABLE_FLAG;
        $protocolNo = FrameWireData::DEFAULT_PROTOCOL_NUMBER;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameWireData::VALID_SOURCE_DATA as $data => $source) {
                $frame = $decoder->decodeFrame($padding . $data, $offset);
                $this->assertSame($frame->getSize(), $size);
                $this->assertSame($frame->getOrigin(), $origin);
                $this->assertSame($frame->isTagged(), $taggedFlag);
                $this->assertSame($frame->isAddressable(), $addressableFlag);
                $this->assertSame($frame->getProtocolNo(), $protocolNo);
                $this->assertSame($frame->getSource(), $source);
            }
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException
     */
    public function testDecodeFrameDataTooShort(): void
    {
        (new FrameDecoder)->decodeFrame(FrameWireData::INVALID_SHORT_DATA);
    }

    public function testDecodeFrameDataTooShortWithOffset(): void
    {
        $failures = 0;
        $decoder = new FrameDecoder();

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            try {
                $decoder->decodeFrame($padding . FrameWireData::INVALID_SHORT_DATA, $offset);
            } catch (InsufficientDataException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count(OffsetTestValues::OFFSETS));
    }

    public function testDecodeFrameWithExampleData(): void
    {
        $frame = (new FrameDecoder)->decodeFrame(ExampleWireData::FRAME_DATA);

        $this->assertSame($frame->getSize(), ExampleWireData::FRAME_SIZE);
        $this->assertSame($frame->getOrigin(), ExampleWireData::FRAME_ORIGIN);
        $this->assertSame($frame->isTagged(), ExampleWireData::FRAME_TAGGED_FLAG);
        $this->assertSame($frame->isAddressable(), ExampleWireData::FRAME_ADDRESSABLE_FLAG);
        $this->assertSame($frame->getProtocolNo(), ExampleWireData::FRAME_PROTOCOL_NUMBER);
        $this->assertSame($frame->getSource(), ExampleWireData::FRAME_SOURCE);
    }
}
