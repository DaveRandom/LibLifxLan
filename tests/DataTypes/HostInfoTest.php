<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\HostInfo;
use PHPUnit\Framework\TestCase;

final class HostInfoTest extends TestCase
{
    public function testSignalProperty(): void
    {
        $signal = 0.1;
        $this->assertSame((new HostInfo($signal, 0, 0))->getSignal(), $signal);
    }

    public function testTxPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $tx) {
            $this->assertSame((new HostInfo(0.0, $tx, 0))->getTx(), $tx);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooLow(): void
    {
        new HostInfo(0.0, 0 - 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooHigh(): void
    {
        new HostInfo(0.0, 0xffffffff + 1, 0);
    }

    public function testRxPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $rx) {
            $this->assertSame((new HostInfo(0.0, 0, $rx))->getRx(), $rx);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooLow(): void
    {
        new HostInfo(0.0, 0, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooHigh(): void
    {
        new HostInfo(0.0, 0, 0xffffffff + 1);
    }
}
