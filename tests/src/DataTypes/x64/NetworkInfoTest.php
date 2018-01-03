<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\x64;

use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class NetworkInfoTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\NetworkInfoTest
{
    use x64Test;

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooLow(): void
    {
        $this->createNetworkInfo(0.0, UINT32_MIN - 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooHigh(): void
    {
        $this->createNetworkInfo(0.0, UINT32_MAX + 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooLow(): void
    {
        $this->createNetworkInfo(0.0, 0, UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooHigh(): void
    {
        $this->createNetworkInfo(0.0, 0, UINT32_MAX + 1);
    }
}
