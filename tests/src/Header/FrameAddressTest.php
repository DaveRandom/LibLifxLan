<?php
/**
 * Created by PhpStorm.
 * User: chris.wright
 * Date: 02/01/2018
 * Time: 16:01
 */

namespace DaveRandom\LibLifxLan\Tests\Header;

use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class FrameAddressTest extends TestCase
{
    private function createTarget(): MacAddress
    {
        return new MacAddress(0, 1, 2, 3, 4, 5);
    }

    public function testTargetProperty(): void
    {
        $target = $this->createTarget();

        $this->assertSame((new FrameAddress($target, false, false, 0))->getTarget(), $target);
    }

    public function testAckRequiredProperty(): void
    {
        $this->assertFalse((new FrameAddress($this->createTarget(), false, false, 0))->isAckRequired());
        $this->assertTrue((new FrameAddress($this->createTarget(), true, false, 0))->isAckRequired());
    }

    public function testResponseRequiredProperty(): void
    {
        $this->assertFalse((new FrameAddress($this->createTarget(), false, false, 0))->isResponseRequired());
        $this->assertTrue((new FrameAddress($this->createTarget(), false, true, 0))->isResponseRequired());
    }

    public function testSequenceNoPropertyValidValues(): void
    {
        foreach ([0, 42, 255] as $value) {
            $this->assertSame((new FrameAddress($this->createTarget(), false, false, $value))->getSequenceNo(), $value);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSequenceNoPropertyValueTooLow(): void
    {
        new FrameAddress($this->createTarget(), false, false, -1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSequenceNoPropertyValueTooHigh(): void
    {
        new FrameAddress($this->createTarget(), false, false, 256);
    }
}
