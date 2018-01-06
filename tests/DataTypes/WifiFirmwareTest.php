<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\WifiFirmware;
use PHPUnit\Framework\TestCase;

final class WifiFirmwareTest extends TestCase
{
    public function testBuildProperty(): void
    {
        $build = new \DateTimeImmutable;
        $this->assertSame((new WifiFirmware($build, 0))->getBuild()->format('u U'), $build->format('u U'));
    }

    public function testVersionPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $version) {
            $this->assertSame((new WifiFirmware(new \DateTimeImmutable, $version))->getVersion(), $version);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooLow(): void
    {
        new WifiFirmware(new \DateTimeImmutable, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooHigh(): void
    {
        new WifiFirmware(new \DateTimeImmutable, 0xffffffff + 1);
    }
}
