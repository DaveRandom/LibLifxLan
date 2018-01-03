<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\x64;

use DaveRandom\LibLifxLan\DataTypes\WifiFirmware;
use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class WifiFirmwareTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\WifiFirmwareTest
{
    use x64Test;

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
