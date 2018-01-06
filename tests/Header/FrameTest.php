<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Header;

use DaveRandom\LibLifxLan\Header\Frame;
use PHPUnit\Framework\TestCase;

final class FrameTest extends TestCase
{
    public function testSizePropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $size) {
            $this->assertSame((new Frame($size, 0, false, false, 0, 0))->getSize(), $size);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSizePropertyValueTooLow(): void
    {
        new Frame(-1, 0, false, false, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSizePropertyValueTooHigh(): void
    {
        new Frame(65536, 0, false, false, 0, 0);
    }

    public function testOriginPropertyValidValues(): void
    {
        foreach ([0, 1, 2, 3] as $origin) {
            $this->assertSame((new Frame(0, $origin, false, false, 0, 0))->getOrigin(), $origin);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testOriginPropertyValueTooLow(): void
    {
        new Frame(0, -1, false, false, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testOriginPropertyValueTooHigh(): void
    {
        new Frame(0, 4, false, false, 0, 0);
    }

    public function testTaggedProperty(): void
    {
        $this->assertFalse((new Frame(0, 0, false, false, 0, 0))->isTagged());
        $this->assertTrue((new Frame(0, 0, true, false, 0, 0))->isTagged());
    }

    public function testAddressable(): void
    {
        $this->assertFalse((new Frame(0, 0, false, false, 0, 0))->isAddressable());
        $this->assertTrue((new Frame(0, 0, false, true, 0, 0))->isAddressable());
    }

    public function testProtocolNoValidValues(): void
    {
        foreach ([0, 42, 4095] as $protocolNo) {
            $this->assertSame((new Frame(0, 0, false, false, $protocolNo, 0))->getProtocolNo(), $protocolNo);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testProtocolNoPropertyValueTooLow(): void
    {
        new Frame(0, 0, false, false, -1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testProtocolNoPropertyValueTooHigh(): void
    {
        new Frame(0, 0, false, false, 4096, 0);
    }

    public function testSourceValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $source) {
            $this->assertSame((new Frame(0, 0, false, false, 0, $source))->getSource(), $source);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSourcePropertyValueTooLow(): void
    {
        new Frame(0, 0, false, false, 0, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSourcePropertyValueTooHigh(): void
    {
        new Frame(0, 0, false, false, 0, 0xffffffff + 1);
    }
}
