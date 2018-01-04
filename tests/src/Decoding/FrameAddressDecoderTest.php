<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Decoding\FrameAddressDecoder;
use DaveRandom\LibLifxLan\Tests\WireData\FrameAddressWireData;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class FrameAddressDecoderTest extends TestCase
{
    public function testDecodeFrameAddressDecodesMacAddressCorrectly()
    {
        $decoder = new FrameAddressDecoder();

        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG_VALUE;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG_VALUE;
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (FrameAddressWireData::VALID_MAC_ADDRESS_DATA as $data => $macAddressOctets) {
            $macAddress = new MacAddress(...$macAddressOctets);
            $frameAddress = $decoder->decodeFrameAddress($data);
            $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
            $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
            $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
            $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
        }
    }

    public function testDecodeFrameAddressDecodesMacAddressCorrectlyWithOffset()
    {
        $decoder = new FrameAddressDecoder();

        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG_VALUE;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG_VALUE;
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameAddressWireData::VALID_MAC_ADDRESS_DATA as $data => $macAddressOctets) {
                $macAddress = new MacAddress(...$macAddressOctets);
                $frameAddress = $decoder->decodeFrameAddress($padding . $data, $offset);
                $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
                $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
                $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
                $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
            }
        }
    }

    public function testDecodeFrameAddressDecodesFlagsCorrectly()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (FrameAddressWireData::VALID_FLAGS_DATA as $data => ['ack' => $ackFlag, 'res' => $resFlag]) {
            $frameAddress = $decoder->decodeFrameAddress($data);
            $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
            $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
            $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
            $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
        }
    }

    public function testDecodeFrameAddressDecodesFlagsCorrectlyWithOffset()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameAddressWireData::VALID_FLAGS_DATA as $data => ['ack' => $ackFlag, 'res' => $resFlag]) {
                $frameAddress = $decoder->decodeFrameAddress($padding . $data, $offset);
                $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
                $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
                $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
                $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
            }
        }
    }

    public function testDecodeFrameAddressDecodesSequenceNumberCorrectly()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG_VALUE;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG_VALUE;

        foreach (FrameAddressWireData::VALID_SEQUENCE_NUMBER_DATA as $data => $sequenceNo) {
            $frameAddress = $decoder->decodeFrameAddress($data);
            $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
            $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
            $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
            $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
        }
    }

    public function testDecodeFrameAddressDecodesSequenceNumberCorrectlyWithOffset()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG_VALUE;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG_VALUE;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameAddressWireData::VALID_SEQUENCE_NUMBER_DATA as $data => $sequenceNo) {
                $frameAddress = $decoder->decodeFrameAddress($padding . $data, $offset);
                $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
                $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
                $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
                $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
            }
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException
     */
    public function testDecodeProtocolHeaderDataTooShort(): void
    {
        (new FrameAddressDecoder)->decodeFrameAddress(FrameAddressWireData::INVALID_SHORT_DATA);
    }

    public function testDecodeProtocolHeaderDataTooShortWithOffset(): void
    {
        $failures = 0;
        $decoder = new FrameAddressDecoder();

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            try {
                $decoder->decodeFrameAddress($padding . FrameAddressWireData::INVALID_SHORT_DATA, $offset);
            } catch (InsufficientDataException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count(OffsetTestValues::OFFSETS));
    }
}
