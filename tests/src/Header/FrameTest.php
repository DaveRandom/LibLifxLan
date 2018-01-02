<?php
/**
 * Created by PhpStorm.
 * User: chris.wright
 * Date: 02/01/2018
 * Time: 16:19
 */

namespace DaveRandom\LibLifxLan\Tests\Header;

use DaveRandom\LibLifxLan\Header\Frame;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class FrameTest extends TestCase
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
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $source) {
            $this->assertSame((new Frame(0, 0, false, false, 0, $source))->getSource(), $source);
        }
    }
}
