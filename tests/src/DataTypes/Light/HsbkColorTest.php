<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use PHPUnit\Framework\TestCase;

final class HsbkColorTest extends TestCase
{
    public function testHuePropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $hue) {
            $this->assertSame((new HsbkColor($hue, 0, 0, 2500))->getHue(), $hue);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testHuePropertyValueTooLow(): void
    {
        new HsbkColor(-1, 0, 0, 2500);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testHuePropertyValueTooHigh(): void
    {
        new HsbkColor(65536, 0, 0, 2500);
    }

    public function testSaturationPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $saturation) {
            $this->assertSame((new HsbkColor(0, $saturation, 0, 2500))->getSaturation(), $saturation);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSaturationPropertyValueTooLow(): void
    {
        new HsbkColor(0, -1, 0, 2500);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testSaturationPropertyValueTooHigh(): void
    {
        new HsbkColor(0, 65536, 0, 2500);
    }

    public function testBrightnessPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $brightness) {
            $this->assertSame((new HsbkColor(0, 0, $brightness, 2500))->getBrightness(), $brightness);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testBrightnessPropertyValueTooLow(): void
    {
        new HsbkColor(0, 0, -1, 2500);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testBrightnessPropertyValueTooHigh(): void
    {
        new HsbkColor(0, 0, 65536, 2500);
    }

    public function testTemperaturePropertyValidValues(): void
    {
        foreach ([2500, 4200, 9500] as $temperature) {
            $this->assertSame((new HsbkColor(0, 0, 0, $temperature))->getTemperature(), $temperature);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTemperaturePropertyValueTooLow(): void
    {
        new HsbkColor(0, 0, 0, 2499);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTemperaturePropertyValueTooHigh(): void
    {
        new HsbkColor(0, 0, 0, 9501);
    }
}
