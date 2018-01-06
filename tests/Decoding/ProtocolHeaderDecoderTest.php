<?php

namespace DaveRandom\LibLifxLan\Tests\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Decoding\ProtocolHeaderDecoder;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\LibLifxLan\Tests\WireData\ProtocolHeaderWireData;
use PHPUnit\Framework\TestCase;

final class ProtocolHeaderDecoderTest extends TestCase
{
    /**
     * @throws InsufficientDataException
     */
    public function testDecodeProtocolHeaderDecodesMessageTypeIdCorrectly(): void
    {
        $decoder = new ProtocolHeaderDecoder;

        foreach (ProtocolHeaderWireData::VALID_MESSAGE_TYPE_ID_DATA as $data => $expectedTypeId) {
            $this->assertSame($decoder->decodeProtocolHeader($data)->getType(), $expectedTypeId);
        }
    }

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeProtocolHeaderDecodesMessageTypeIdCorrectlyWithOffset(): void
    {
        $decoder = new ProtocolHeaderDecoder;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (ProtocolHeaderWireData::VALID_MESSAGE_TYPE_ID_DATA as $data => $expectedTypeId) {
                $protocolHeader = $decoder->decodeProtocolHeader($padding . $data, $offset);
                $this->assertSame($protocolHeader->getType(), $expectedTypeId);
            }
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException
     */
    public function testDecodeProtocolHeaderDataTooShort(): void
    {
        (new ProtocolHeaderDecoder)->decodeProtocolHeader(ProtocolHeaderWireData::INVALID_SHORT_DATA);
    }

    public function testDecodeProtocolHeaderDataTooShortWithOffset(): void
    {
        $failures = 0;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            try {
                (new ProtocolHeaderDecoder)->decodeProtocolHeader($padding . ProtocolHeaderWireData::INVALID_SHORT_DATA, $offset);
            } catch (InsufficientDataException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count(OffsetTestValues::OFFSETS));
    }

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeProtocolHeaderWithExampleData(): void
    {
        $protocolHeader = (new ProtocolHeaderDecoder)->decodeProtocolHeader(ExampleWireData::PROTOCOL_HEADER_DATA);

        $this->assertSame($protocolHeader->getType(), ExampleWireData::PROTOCOL_HEADER_MESSAGE_TYPE_ID);
    }
}
