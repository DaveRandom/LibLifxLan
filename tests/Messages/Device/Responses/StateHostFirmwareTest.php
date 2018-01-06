<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\HostFirmware;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateHostFirmware;
use PHPUnit\Framework\TestCase;

final class StateHostFirmwareTest extends TestCase
{
    private $firmware;

    protected function setUp(): void
    {
        $this->firmware = new HostFirmware(new \DateTime, 0);
    }

    public function testHostFirmwareProperty(): void
    {
        $this->assertSame((new StateHostFirmware($this->firmware))->getHostFirmware(), $this->firmware);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateHostFirmware($this->firmware))->getTypeId(), StateHostFirmware::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateHostFirmware($this->firmware))->getWireSize(), StateHostFirmware::WIRE_SIZE);
    }
}
