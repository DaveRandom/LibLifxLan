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

    public function testRxPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $rx) {
            $this->assertSame((new WifiInfo(0.0, 0, $rx))->getRx(), $rx);
        }
    }
}
