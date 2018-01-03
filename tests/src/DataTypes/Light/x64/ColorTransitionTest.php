<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light\x64;

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class ColorTransitionTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\Light\ColorTransitionTest
{
    use x64Test;

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testDurationPropertyValueTooLow(): void
    {
        new ColorTransition(new HsbkColor(0, 0, 0, 2500), UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testDurationPropertyValueTooHigh(): void
    {
        new ColorTransition(new HsbkColor(0, 0, 0, 2500), UINT32_MAX + 1);
    }
}
