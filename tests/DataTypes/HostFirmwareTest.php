<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\HostFirmware;
use PHPUnit\Framework\TestCase;

final class HostFirmwareTest extends TestCase
{
    public function testBuildProperty(): void
    {
        $build = new \DateTimeImmutable;
        $this->assertSame((new HostFirmware($build, 0))->getBuild()->format('u U'), $build->format('u U'));
    }

    public function testVersionPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $version) {
            $this->assertSame((new HostFirmware(new \DateTimeImmutable, $version))->getVersion(), $version);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooLow(): void
    {
        new HostFirmware(new \DateTimeImmutable, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooHigh(): void
    {
        new HostFirmware(new \DateTimeImmutable, 0xffffffff + 1);
    }
}
