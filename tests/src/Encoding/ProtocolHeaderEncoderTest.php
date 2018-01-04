<?php

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\Encoding\ProtocolHeaderEncoder;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use PHPUnit\Framework\TestCase;

final class ProtocolHeaderEncoderTest extends TestCase
{
    public function testEncodeProtocolHeader(): void
    {
        $encoder = new ProtocolHeaderEncoder;

        $this->assertSame(
            $encoder->encodeProtocolHeader(new ProtocolHeader(0)),
            "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00"
        );

        $this->assertSame(
            $encoder->encodeProtocolHeader(new ProtocolHeader(0x00ff)),
            "\x00\x00\x00\x00\x00\x00\x00\x00\xff\x00\x00\x00"
        );

        $this->assertSame(
            $encoder->encodeProtocolHeader(new ProtocolHeader(0xff00)),
            "\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\x00\x00"
        );
    }
}
