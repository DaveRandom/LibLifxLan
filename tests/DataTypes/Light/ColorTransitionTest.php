<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use PHPUnit\Framework\TestCase;

final class ColorTransitionTest extends TestCase
{
    public function testColorProperty(): void
    {
        $color = new HsbkColor(0, 0, 0, 2500);
        $this->assertSame((new ColorTransition($color, 0))->getColor(), $color);
    }

    public function testDurationPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $duration) {
            $this->assertSame((new ColorTransition(new HsbkColor(0, 0, 0, 2500), $duration))->getDuration(), $duration);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testDurationPropertyValueTooLow(): void
    {
        new ColorTransition(new HsbkColor(0, 0, 0, 2500), 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testDurationPropertyValueTooHigh(): void
    {
        new ColorTransition(new HsbkColor(0, 0, 0, 2500), 0xffffffff + 1);
    }
}
