<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\x64;

use DaveRandom\LibLifxLan\DataTypes\WifiInfo;
use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class WifiInfoTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\WifiInfoTest
{
    use x64Test;

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooLow(): void
    {
        new WifiInfo(0.0, UINT32_MIN - 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooHigh(): void
    {
        new WifiInfo(0.0, UINT32_MAX + 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooLow(): void
    {
        new WifiInfo(0.0, 0, UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooHigh(): void
    {
        new WifiInfo(0.0, 0, UINT32_MAX + 1);
    }
}
