<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\EchoRequest;
use PHPUnit\Framework\TestCase;

final class EchoRequestTest extends TestCase
{
    private $payload;

    protected function setUp(): void
    {
        $this->payload = \str_pad('', 64, 'test');
    }

    public function testPayloadPropertyValidValues(): void
    {
        $this->assertSame((new EchoRequest($this->payload))->getPayload(), $this->payload);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPayloadPropertyValueTooShort(): void
    {
        new EchoRequest(\str_pad('', 63, 'test'));
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPayloadPropertyValueTooLong(): void
    {
        new EchoRequest(\str_pad('', 65, 'test'));
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new EchoRequest($this->payload))->getTypeId(), EchoRequest::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new EchoRequest($this->payload))->getWireSize(), EchoRequest::WIRE_SIZE);
    }
}
