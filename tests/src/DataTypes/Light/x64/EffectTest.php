<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light\x64;

use DaveRandom\LibLifxLan\DataTypes\Light\Effect;
use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class EffectTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\Light\EffectTest
{
    use x64Test;

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPeriodPropertyValueTooLow(): void
    {
        new Effect(false, $this->color, UINT32_MIN - 1, 0.0, 0, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPeriodPropertyValueTooHigh(): void
    {
        new Effect(false, $this->color, UINT32_MAX + 1, 0.0, 0, 0, 0);
    }
}
