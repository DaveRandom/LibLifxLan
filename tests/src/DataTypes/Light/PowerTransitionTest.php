<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Light\PowerTransition;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class PowerTransitionTest extends TestCase
{
    public function testPowerPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $level) {
            $this->assertSame((new PowerTransition($level, 0))->getLevel(), $level);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPowerPropertyValueTooLow(): void
    {
        new PowerTransition(-1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPowerPropertyValueTooHigh(): void
    {
        new PowerTransition(65536, 0);
    }

    public function testDurationPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $duration) {
            $this->assertSame((new PowerTransition(0, $duration))->getDuration(), $duration);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testDurationPropertyValueTooLow(): void
    {
        new PowerTransition(0, UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testDurationPropertyValueTooHigh(): void
    {
        new PowerTransition(0, UINT32_MAX + 1);
    }
}
