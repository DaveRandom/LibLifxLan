<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Version;
use PHPUnit\Framework\TestCase;

final class VersionTest extends TestCase
{
    public function testVendorPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $vendor) {
            $this->assertSame((new Version($vendor, 0, 0))->getVendor(), $vendor);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVendorPropertyValueTooLow(): void
    {
        new Version(0 - 1, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVendorPropertyValueTooHigh(): void
    {
        new Version(0xffffffff + 1, 0, 0);
    }

    public function testProductPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $product) {
            $this->assertSame((new Version(0, $product, 0))->getProduct(), $product);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testProductPropertyValueTooLow(): void
    {
        new Version(0, 0 - 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testProductPropertyValueTooHigh(): void
    {
        new Version(0, 0xffffffff + 1, 0);
    }

    public function testVersionPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $version) {
            $this->assertSame((new Version(0, 0, $version))->getVersion(), $version);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooLow(): void
    {
        new Version(0, 0, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testVersionPropertyValueTooHigh(): void
    {
        new Version(0, 0, 0xffffffff + 1);
    }
}
