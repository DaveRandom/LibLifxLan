<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\NetworkInfo;
use PHPUnit\Framework\TestCase;

final class NetworkInfoTest extends TestCase
{
    protected function createNetworkInfo(float $signal, int $tx, int $rx): NetworkInfo
    {
        return new class($signal, $tx, $rx) extends NetworkInfo {
            /**
             * @param float $signal
             * @param int $tx
             * @param int $rx
             * @throws \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
             */
            public function __construct(float $signal, int $tx, int $rx)
            {
                parent::__construct($signal, $tx, $rx);
            }
        };
    }

    public function testSignalProperty(): void
    {
        $signal = 0.1;
        $this->assertSame($this->createNetworkInfo($signal, 0, 0)->getSignal(), $signal);
    }

    public function testTxPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $tx) {
            $this->assertSame($this->createNetworkInfo(0.0, $tx, 0)->getTx(), $tx);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooLow(): void
    {
        $this->createNetworkInfo(0.0, 0 - 1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTxPropertyValueTooHigh(): void
    {
        $this->createNetworkInfo(0.0, 0xffffffff + 1, 0);
    }

    public function testRxPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $rx) {
            $this->assertSame($this->createNetworkInfo(0.0, 0, $rx)->getRx(), $rx);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooLow(): void
    {
        $this->createNetworkInfo(0.0, 0, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testRxPropertyValueTooHigh(): void
    {
        $this->createNetworkInfo(0.0, 0, 0xffffffff + 1);
    }
}
