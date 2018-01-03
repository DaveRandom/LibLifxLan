<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Version;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class VersionTest extends TestCase
{
    public function testVendorPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $vendor) {
            $this->assertSame((new Version($vendor, 0, 0))->getVendor(), $vendor);
        }
    }

    public function testProductPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $product) {
            $this->assertSame((new Version(0, $product, 0))->getProduct(), $product);
        }
    }

    public function testVersionPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $version) {
            $this->assertSame((new Version(0, 0, $version))->getVersion(), $version);
        }
    }
}
