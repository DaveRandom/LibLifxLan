<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\x64;

use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class FirmwareTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\FirmwareTest
{
    use x64Test;

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooLow(): void
    {
        $this->createFirmware(new \DateTimeImmutable, UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooHigh(): void
    {
        $this->createFirmware(new \DateTimeImmutable, UINT32_MAX + 1);
    }
}
