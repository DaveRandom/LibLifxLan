<?php

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Commands;

use DaveRandom\LibLifxLan\Messages\Device\Commands\SetPower;
use PHPUnit\Framework\TestCase;

final class SetPowerTest extends TestCase
{
    public function testLevelPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $level) {
            $this->assertSame((new SetPower($level))->getLevel(), $level);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testLevelPropertyValueTooLow(): void
    {
        new SetPower(-1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testLevelPropertyValueTooHigh(): void
    {
        new SetPower(65536);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetPower(0))->getTypeId(), SetPower::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetPower(0))->getWireSize(), SetPower::WIRE_SIZE);
    }
}
