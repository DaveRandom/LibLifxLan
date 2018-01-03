<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\x64;

use DaveRandom\LibLifxLan\DataTypes\Version;
use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class VersionTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\VersionTest
{
    use x64Test;

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVendorPropertyValueTooLow(): void
    {
        new Version(UINT32_MIN - 1, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVendorPropertyValueTooHigh(): void
    {
        new Version(UINT32_MAX + 1, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testProductPropertyValueTooLow(): void
    {
        new Version(0, UINT32_MIN - 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testProductPropertyValueTooHigh(): void
    {
        new Version(0, UINT32_MAX + 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooLow(): void
    {
        new Version(0, 0, UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooHigh(): void
    {
        new Version(0, 0, UINT32_MAX + 1);
    }
}
