<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light\x64;

use DaveRandom\LibLifxLan\DataTypes\Light\PowerTransition;
use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class PowerTransitionTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\Light\PowerTransitionTest
{
    use x64Test;

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
