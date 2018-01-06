<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Header;

use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class FrameAddressTest extends TestCase
{
    private $target;

    protected function setUp(): void
    {
        $this->target = new MacAddress(0, 1, 2, 3, 4, 5);
    }

    public function testTargetProperty(): void
    {
        $this->assertSame((new FrameAddress($this->target, false, false, 0))->getTarget(), $this->target);
    }

    public function testAckRequiredProperty(): void
    {
        $this->assertFalse((new FrameAddress($this->target, false, false, 0))->isAckRequired());
        $this->assertTrue((new FrameAddress($this->target, true, false, 0))->isAckRequired());
    }

    public function testResponseRequiredProperty(): void
    {
        $this->assertFalse((new FrameAddress($this->target, false, false, 0))->isResponseRequired());
        $this->assertTrue((new FrameAddress($this->target, false, true, 0))->isResponseRequired());
    }

    public function testSequenceNoPropertyValidValues(): void
    {
        foreach ([0, 42, 255] as $value) {
            $this->assertSame((new FrameAddress($this->target, false, false, $value))->getSequenceNo(), $value);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSequenceNoPropertyValueTooLow(): void
    {
        new FrameAddress($this->target, false, false, -1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSequenceNoPropertyValueTooHigh(): void
    {
        new FrameAddress($this->target, false, false, 256);
    }
}
