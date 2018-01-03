<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\HostInfo;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class HostInfoTest extends TestCase
{
    public function testSignalProperty(): void
    {
        $signal = 0.1;
        $this->assertSame((new HostInfo($signal, 0, 0))->getSignal(), $signal);
    }

    public function testTxPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $tx) {
            $this->assertSame((new HostInfo(0.0, $tx, 0))->getTx(), $tx);
        }
    }

    public function testRxPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $rx) {
            $this->assertSame((new HostInfo(0.0, 0, $rx))->getRx(), $rx);
        }
    }
}
