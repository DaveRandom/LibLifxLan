<?php

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\Encoding\ProtocolHeaderEncoder;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\LibLifxLan\Tests\WireData\ProtocolHeaderWireData;
use PHPUnit\Framework\TestCase;

final class ProtocolHeaderEncoderTest extends TestCase
{
    public function testEncodeProtocolHeaderEncodesMessageTypeIdCorrectly(): void
    {
        $encoder = new ProtocolHeaderEncoder;

        foreach (ProtocolHeaderWireData::VALID_MESSAGE_TYPE_ID_DATA as $expectedData => $messageTypeId) {
            $this->assertSame($encoder->encodeProtocolHeader(new ProtocolHeader($messageTypeId)), $expectedData);
        }
    }
}
