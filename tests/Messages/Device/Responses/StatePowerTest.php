<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\Messages\Device\Responses\StatePower;
use PHPUnit\Framework\TestCase;

final class StatePowerTest extends TestCase
{
    public function testLevelPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $level) {
            $this->assertSame((new StatePower($level))->getLevel(), $level);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testLevelPropertyValueTooLow(): void
    {
        new StatePower(-1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testLevelPropertyValueTooHigh(): void
    {
        new StatePower(65536);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StatePower(0))->getTypeId(), StatePower::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StatePower(0))->getWireSize(), StatePower::WIRE_SIZE);
    }
}
