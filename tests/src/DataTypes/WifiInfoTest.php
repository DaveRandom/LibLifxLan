<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\WifiInfo;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class WifiInfoTest extends TestCase
{
    public function testSignalProperty(): void
    {
        $signal = 0.1;
        $this->assertSame((new WifiInfo($signal, 0, 0))->getSignal(), $signal);
    }

    public function testTxPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $tx) {
            $this->assertSame((new WifiInfo(0.0, $tx, 0))->getTx(), $tx);
        }
    }

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

    public function testRxPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $rx) {
            $this->assertSame((new WifiInfo(0.0, 0, $rx))->getRx(), $rx);
        }
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
