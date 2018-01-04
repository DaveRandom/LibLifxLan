<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\Encoding\FrameAddressEncoder;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Tests\WireData\FrameAddressWireData;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class FrameAddressEncoderTest extends TestCase
{
    public function testEncodeProtocolHeaderEncodesMacAddressCorrectly(): void
    {
        $encoder = new FrameAddressEncoder();

        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG_VALUE;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG_VALUE;
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (FrameAddressWireData::VALID_MAC_ADDRESS_DATA as $expectedData => $macAddressOctets) {
            $frameAddress = new FrameAddress(new MacAddress(...$macAddressOctets), $ackFlag, $resFlag, $sequenceNo);
            $this->assertSame($encoder->encodeFrameAddress($frameAddress), $expectedData);
        }
    }

    public function testEncodeProtocolHeaderEncodesFlagsCorrectly(): void
    {
        $encoder = new FrameAddressEncoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $sequenceNo = FrameAddressWireData::DEFAULT_SEQUENCE_NUMBER;

        foreach (FrameAddressWireData::VALID_FLAGS_DATA as $expectedData => ['ack' => $ackFlag, 'res' => $resFlag]) {
            $frameAddress = new FrameAddress($macAddress, $ackFlag, $resFlag, $sequenceNo);
            $this->assertSame($encoder->encodeFrameAddress($frameAddress), $expectedData);
        }
    }

    public function testEncodeProtocolHeaderEncodesSequenceNumberCorrectly(): void
    {
        $encoder = new FrameAddressEncoder();

        $macAddress = new MacAddress(...FrameAddressWireData::DEFAULT_MAC_ADDRESS_OCTETS);
        $ackFlag = FrameAddressWireData::DEFAULT_ACK_FLAG_VALUE;
        $resFlag = FrameAddressWireData::DEFAULT_RES_FLAG_VALUE;

        foreach (FrameAddressWireData::VALID_SEQUENCE_NUMBER_DATA as $expectedData => $sequenceNo) {
            $frameAddress = new FrameAddress($macAddress, $ackFlag, $resFlag, $sequenceNo);
            $this->assertSame($encoder->encodeFrameAddress($frameAddress), $expectedData);
        }
    }
}
