<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Commands;

use DaveRandom\LibLifxLan\DataTypes\Light\Effect;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetWaveform;
use PHPUnit\Framework\TestCase;

final class SetWaveformTest extends TestCase
{
    private $effect;

    protected function setUp(): void
    {
        $this->effect = new Effect(false, new HsbkColor(0, 0, 0, 2500), 0, 0.0, 0, 0, 0);
    }

    public function testEffectProperty(): void
    {
        $this->assertSame((new SetWaveform($this->effect))->getEffect(), $this->effect);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetWaveform($this->effect))->getTypeId(), SetWaveform::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetWaveform($this->effect))->getWireSize(), SetWaveform::WIRE_SIZE);
    }
}
