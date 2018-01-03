<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class ColorTransitionTest extends TestCase
{
    public function testColorProperty(): void
    {
        $color = new HsbkColor(0, 0, 0, 2500);
        $this->assertSame((new ColorTransition($color, 0))->getColor(), $color);
    }

    public function testDurationPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $duration) {
            $this->assertSame((new ColorTransition(new HsbkColor(0, 0, 0, 2500), $duration))->getDuration(), $duration);
        }
    }
}
