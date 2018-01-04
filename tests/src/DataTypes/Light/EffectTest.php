<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Light\Effect;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use PHPUnit\Framework\TestCase;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

class EffectTest extends TestCase
{
    protected $color;

    protected function setUp(): void
    {
        $this->color = new HsbkColor(0, 0, 0, 2500);
    }

    public function testIsTransient(): void
    {
        $this->assertFalse((new Effect(false, $this->color, 0, 0.0, 0, 0, 0))->isTransient());
        $this->assertTrue((new Effect(true, $this->color, 0, 0.0, 0, 0, 0))->isTransient());
    }

    public function testColorProperty(): void
    {
        $this->assertSame((new Effect(false, $this->color, 0, 0.0, 0, 0, 0))->getColor(), $this->color);
    }

    public function testPeriodPropertyValidValues(): void
    {
        foreach ([UINT32_MIN, 42, UINT32_MAX] as $period) {
            $this->assertSame((new Effect(false, $this->color, $period, 0.0, 0, 0, 0))->getPeriod(), $period);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPeriodPropertyValueTooLow(): void
    {
        new Effect(false, $this->color, UINT32_MIN - 1, 0.0, 0, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPeriodPropertyValueTooHigh(): void
    {
        new Effect(false, $this->color, UINT32_MAX + 1, 0.0, 0, 0, 0);
    }

    public function testCyclesProperty(): void
    {
        $this->assertSame((new Effect(false, $this->color, 0, 0.1, 0, 0, 0))->getCycles(), 0.1);
    }

    public function testSkewRatioPropertyValidValues(): void
    {
        foreach ([-32768, 42, 32767] as $skewRatio) {
            $this->assertSame((new Effect(false, $this->color, 0, 0.0, $skewRatio, 0, 0))->getSkewRatio(), $skewRatio);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSkewRatioPropertyValueTooLow(): void
    {
        new Effect(false, $this->color, 0, 0.0, -32769, 0, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSkewRatioPropertyValueTooHigh(): void
    {
        new Effect(false, $this->color, 0, 0.0, 32768, 0, 0);
    }

    public function testWaveformPropertyValidValues(): void
    {
        foreach ([0, 42, 255] as $waveform) {
            $this->assertSame((new Effect(false, $this->color, 0, 0.0, 0, $waveform, 0))->getWaveform(), $waveform);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testWaveformPropertyValueTooLow(): void
    {
        new Effect(false, $this->color, 0, 0.0, 0, -1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testWaveformPropertyValueTooHigh(): void
    {
        new Effect(false, $this->color, 0, 0.0, 0, 256, 0);
    }

    public function testOptionsProperty(): void
    {
        $this->assertSame((new Effect(false, $this->color, 0, 0.0, 0, 0, 1234))->getOptions(), 1234);
    }

    public function testHasOption(): void
    {
        $effect = new Effect(false, $this->color, 0, 0.0, 0, 0, 0);
        $this->assertFalse($effect->hasOption(Effect::SET_HUE));
        $this->assertFalse($effect->hasOption(Effect::SET_SATURATION));
        $this->assertFalse($effect->hasOption(Effect::SET_BRIGHTNESS));
        $this->assertFalse($effect->hasOption(Effect::SET_TEMPERATURE));

        $effect = new Effect(false, $this->color, 0, 0.0, 0, 0, Effect::SET_HUE);
        $this->assertTrue($effect->hasOption(Effect::SET_HUE));
        $this->assertFalse($effect->hasOption(Effect::SET_SATURATION));
        $this->assertFalse($effect->hasOption(Effect::SET_BRIGHTNESS));
        $this->assertFalse($effect->hasOption(Effect::SET_TEMPERATURE));

        $effect = new Effect(false, $this->color, 0, 0.0, 0, 0, Effect::SET_SATURATION);
        $this->assertFalse($effect->hasOption(Effect::SET_HUE));
        $this->assertTrue($effect->hasOption(Effect::SET_SATURATION));
        $this->assertFalse($effect->hasOption(Effect::SET_BRIGHTNESS));
        $this->assertFalse($effect->hasOption(Effect::SET_TEMPERATURE));

        $effect = new Effect(false, $this->color, 0, 0.0, 0, 0, Effect::SET_BRIGHTNESS);
        $this->assertFalse($effect->hasOption(Effect::SET_HUE));
        $this->assertFalse($effect->hasOption(Effect::SET_SATURATION));
        $this->assertTrue($effect->hasOption(Effect::SET_BRIGHTNESS));
        $this->assertFalse($effect->hasOption(Effect::SET_TEMPERATURE));

        $effect = new Effect(false, $this->color, 0, 0.0, 0, 0, Effect::SET_TEMPERATURE);
        $this->assertFalse($effect->hasOption(Effect::SET_HUE));
        $this->assertFalse($effect->hasOption(Effect::SET_SATURATION));
        $this->assertFalse($effect->hasOption(Effect::SET_BRIGHTNESS));
        $this->assertTrue($effect->hasOption(Effect::SET_TEMPERATURE));

        $effect = new Effect(false, $this->color, 0, 0.0, 0, 0, Effect::SET_ALL);
        $this->assertTrue($effect->hasOption(Effect::SET_HUE));
        $this->assertTrue($effect->hasOption(Effect::SET_SATURATION));
        $this->assertTrue($effect->hasOption(Effect::SET_BRIGHTNESS));
        $this->assertTrue($effect->hasOption(Effect::SET_TEMPERATURE));

    }
}
