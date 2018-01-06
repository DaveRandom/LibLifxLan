<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Decoding;

use DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LibLifxLan\Decoding\FrameAddressDecoder;
use DaveRandom\LibLifxLan\Tests\WireData\ExampleWireData;
use DaveRandom\LibLifxLan\Tests\WireData\FrameAddressWireData;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class FrameAddressDecoderTest extends TestCase
{
    /**
     * @throws InsufficientDataException
     */
    public function testDecodeFrameAddressDecodesMacAddressCorrectly()
    {
        $decoder = new FrameAddressDecoder();

        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG;
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (FrameAddressWireData::VALID_MAC_ADDRESS_DATA as $data => $macAddressOctets) {
            $macAddress = new MacAddress(...$macAddressOctets);
            $frameAddress = $decoder->decodeFrameAddress($data);
            $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
            $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
            $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
            $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
        }
    }

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeFrameAddressDecodesMacAddressCorrectlyWithOffset()
    {
        $decoder = new FrameAddressDecoder();

        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG;
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameAddressWireData::VALID_MAC_ADDRESS_DATA as $data => $macAddressOctets) {
                $macAddress = new MacAddress(...$macAddressOctets);
                $frameAddress = $decoder->decodeFrameAddress($padding . $data, $offset);
                $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
                $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
                $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
                $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
            }
        }
    }

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeFrameAddressDecodesFlagsCorrectly()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (FrameAddressWireData::VALID_FLAGS_DATA as $data => ['ack' => $ackFlag, 'res' => $resFlag]) {
            $frameAddress = $decoder->decodeFrameAddress($data);
            $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
            $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
            $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
            $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
        }
    }

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeFrameAddressDecodesFlagsCorrectlyWithOffset()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameAddressWireData::VALID_FLAGS_DATA as $data => ['ack' => $ackFlag, 'res' => $resFlag]) {
                $frameAddress = $decoder->decodeFrameAddress($padding . $data, $offset);
                $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
                $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
                $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
                $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
            }
        }
    }

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeFrameAddressDecodesSequenceNumberCorrectly()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG;

        foreach (FrameAddressWireData::VALID_SEQUENCE_NUMBER_DATA as $data => $sequenceNo) {
            $frameAddress = $decoder->decodeFrameAddress($data);
            $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
            $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
            $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
            $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
        }
    }

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeFrameAddressDecodesSequenceNumberCorrectlyWithOffset()
    {
        $decoder = new FrameAddressDecoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG;

        foreach (OffsetTestValues::OFFSETS as $offset) {
            $padding = \str_repeat("\x00", $offset);

            foreach (FrameAddressWireData::VALID_SEQUENCE_NUMBER_DATA as $data => $sequenceNo) {
                $frameAddress = $decoder->decodeFrameAddress($padding . $data, $offset);
                $this->assertTrue($frameAddress->getTarget()->equals($macAddress));
                $this->assertSame($frameAddress->isAckRequired(), $ackFlag);
                $this->assertSame($frameAddress->isResponseRequired(), $resFlag);
                $this->assertSame($frameAddress->getSequenceNo(), $sequenceNo);
            }
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException
     */
    public function testDecodeFrameAddressDataTooShort(): void
    {
        (new FrameAddressDecoder)->decodeFrameAddress(FrameAddressWireData::INVALID_SHORT_DATA);
    }

    public function testDecodeFrameAddressDataTooShortWithOffset(): void
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

    /**
     * @throws InsufficientDataException
     */
    public function testDecodeFrameAddressWithExampleData(): void
    {
        $frameAddress = (new FrameAddressDecoder)->decodeFrameAddress(ExampleWireData::FRAME_ADDRESS_DATA);

        $this->assertTrue($frameAddress->getTarget()->equals(new MacAddress(...ExampleWireData::FRAME_ADDRESS_TARGET_OCTETS)));
        $this->assertSame($frameAddress->isAckRequired(), ExampleWireData::FRAME_ADDRESS_ACK_FLAG);
        $this->assertSame($frameAddress->isResponseRequired(), ExampleWireData::FRAME_ADDRESS_RES_FLAG);
        $this->assertSame($frameAddress->getSequenceNo(), ExampleWireData::FRAME_ADDRESS_SEQUENCE_NO);
    }
}
