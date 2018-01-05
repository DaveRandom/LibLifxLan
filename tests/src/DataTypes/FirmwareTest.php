<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Firmware;
use PHPUnit\Framework\TestCase;

class FirmwareTest extends TestCase
{
    protected function createFirmware(\DateTimeInterface $build, int $version): Firmware
    {
        return new class($build, $version) extends Firmware {
            /**
             * @param \DateTimeInterface $build
             * @param int $version
             * @throws \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
             */
            public function __construct(\DateTimeInterface $build, int $version)
            {
                parent::__construct($build, $version);
            }
        };
    }

    public function testBuildProperty(): void
    {
        $build = new \DateTimeImmutable;
        $this->assertSame($this->createFirmware($build, 0)->getBuild()->format('u U'), $build->format('u U'));
    }

    public function testVersionPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $version) {
            $this->assertSame($this->createFirmware(new \DateTimeImmutable, $version)->getVersion(), $version);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooLow(): void
    {
        $this->createFirmware(new \DateTimeImmutable, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooHigh(): void
    {
        $this->createFirmware(new \DateTimeImmutable, 0xffffffff + 1);
    }
}
