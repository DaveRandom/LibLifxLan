<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Test\Messages;

use DaveRandom\LibLifxLan\Messages\UnknownMessage;
use PHPUnit\Framework\TestCase;

final class UnknownMessageTest extends TestCase
{
    private $messageTypeId = 12345;
    private $data = 'This is some test data';

    public function testDataProperty(): void
    {
        $this->assertSame((new UnknownMessage($this->messageTypeId, $this->data))->getData(), $this->data);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new UnknownMessage($this->messageTypeId, $this->data))->getTypeId(), $this->messageTypeId);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new UnknownMessage($this->messageTypeId, $this->data))->getWireSize(), \strlen($this->data));
    }
}
