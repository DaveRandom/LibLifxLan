<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Commands;

use DaveRandom\LibLifxLan\Messages\Light\Commands\SetInfrared;
use PHPUnit\Framework\TestCase;

final class SetInfraredTest extends TestCase
{
    public function testBrightnessPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $brightness) {
            $this->assertSame((new SetInfrared($brightness))->getBrightness(), $brightness);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testBrightnessPropertyValueTooLow(): void
    {
        new SetInfrared(-1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testBrightnessPropertyValueTooHigh(): void
    {
        new SetInfrared(65536);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetInfrared(0))->getTypeId(), SetInfrared::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetInfrared(0))->getWireSize(), SetInfrared::WIRE_SIZE);
    }
}
