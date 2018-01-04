<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\WifiFirmware;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class WifiFirmwareTest extends TestCase
{
    public function testBuildProperty(): void
    {
        $build = new \DateTimeImmutable;
        $this->assertSame((new WifiFirmware($build, 0))->getBuild()->format('u U'), $build->format('u U'));
    }

    public function testVersionPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $version) {
            $this->assertSame((new WifiFirmware(new \DateTimeImmutable, $version))->getVersion(), $version);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooLow(): void
    {
        new WifiFirmware(new \DateTimeImmutable, UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooHigh(): void
    {
        new WifiFirmware(new \DateTimeImmutable, UINT32_MAX + 1);
    }
}
