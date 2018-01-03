<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Firmware;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

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
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $version) {
            $this->assertSame($this->createFirmware(new \DateTimeImmutable, $version)->getVersion(), $version);
        }
    }
}
