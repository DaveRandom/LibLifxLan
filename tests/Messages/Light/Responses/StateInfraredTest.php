<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Responses;

use DaveRandom\LibLifxLan\Messages\Light\Responses\StateInfrared;
use PHPUnit\Framework\TestCase;

final class StateInfraredTest extends TestCase
{
    public function testBrightnessPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $brightness) {
            $this->assertSame((new StateInfrared($brightness))->getBrightness(), $brightness);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testBrightnessPropertyValueTooLow(): void
    {
        new StateInfrared(-1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testBrightnessPropertyValueTooHigh(): void
    {
        new StateInfrared(65536);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateInfrared(0))->getTypeId(), StateInfrared::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateInfrared(0))->getWireSize(), StateInfrared::WIRE_SIZE);
    }
}
