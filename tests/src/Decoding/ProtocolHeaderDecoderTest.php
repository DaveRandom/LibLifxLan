<?php
/**
 * Created by PhpStorm.
 * User: chris.wright
 * Date: 04/01/2018
 * Time: 00:17
 */

namespace DaveRandom\LibLifxLan\Tests\Decoding;

use DaveRandom\LibLifxLan\Decoding\ProtocolHeaderDecoder;
use PHPUnit\Framework\TestCase;

class ProtocolHeaderDecoderTest extends TestCase
{
    public function testDecodeProtocolHeader(): void
    {
        $decoder = new ProtocolHeaderDecoder;

        $protocolHeader = $decoder->decodeProtocolHeader("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00");
        $this->assertSame($protocolHeader->getType(), 0);

        $protocolHeader = $decoder->decodeProtocolHeader("\x00\x00\x00\x00\x00\x00\x00\x00\xff\x00\x00\x00");
        $this->assertSame($protocolHeader->getType(), 0x00ff);

        $protocolHeader = $decoder->decodeProtocolHeader("\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\x00\x00");
        $this->assertSame($protocolHeader->getType(), 0xff00);
    }

    public function testDecodeProtocolHeaderWithOffset(): void
    {
        $decoder = new ProtocolHeaderDecoder;

        $protocolHeader = $decoder->decodeProtocolHeader("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00", 2);
        $this->assertSame($protocolHeader->getType(), 0);

        $protocolHeader = $decoder->decodeProtocolHeader("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\x00\x00\x00", 2);
        $this->assertSame($protocolHeader->getType(), 0x00ff);

        $protocolHeader = $decoder->decodeProtocolHeader("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\x00\x00", 2);
        $this->assertSame($protocolHeader->getType(), 0xff00);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InsufficientDataException
     */
    public function testDecodeProtocolHeaderDataTooShort(): void
    {
        (new ProtocolHeaderDecoder)->decodeProtocolHeader("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00");
    }
}
