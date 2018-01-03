<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\WifiFirmware;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateWifiFirmware;
use PHPUnit\Framework\TestCase;

final class StateWifiFirmwareTest extends TestCase
{
    private $firmware;

    protected function setUp(): void
    {
        $this->firmware = new WifiFirmware(new \DateTime, 0);
    }

    public function testWifiFirmwareProperty(): void
    {
        $this->assertSame((new StateWifiFirmware($this->firmware))->getWifiFirmware(), $this->firmware);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateWifiFirmware($this->firmware))->getTypeId(), StateWifiFirmware::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateWifiFirmware($this->firmware))->getWireSize(), StateWifiFirmware::WIRE_SIZE);
    }
}
