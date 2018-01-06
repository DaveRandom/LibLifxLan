<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\Messages\Device\Responses\EchoResponse;
use PHPUnit\Framework\TestCase;

final class EchoResponseTest extends TestCase
{
    private $payload;

    protected function setUp(): void
    {
        $this->payload = \str_pad('', 64, 'test');
    }

    public function testPayloadPropertyValidValues(): void
    {
        $this->assertSame((new EchoResponse($this->payload))->getPayload(), $this->payload);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPayloadPropertyValueTooShort(): void
    {
        new EchoResponse(\str_pad('', 63, 'test'));
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPayloadPropertyValueTooLong(): void
    {
        new EchoResponse(\str_pad('', 65, 'test'));
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new EchoResponse($this->payload))->getTypeId(), EchoResponse::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new EchoResponse($this->payload))->getWireSize(), EchoResponse::WIRE_SIZE);
    }
}
