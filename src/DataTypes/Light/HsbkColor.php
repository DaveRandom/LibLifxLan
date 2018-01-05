<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_int_range;
use function DaveRandom\LibLifxLan\validate_uint16;

final class HsbkColor
{
    private $hue;
    private $saturation;
    private $brightness;
    private $temperature;

    /**
     * @param int $hue
     * @param int $saturation
     * @param int $brightness
     * @param int $temperature
     * @throws InvalidValueException
     */
    public function __construct(int $hue, int $saturation, int $brightness, int $temperature)
    {
        $this->hue = validate_uint16('Hue', $hue);
        $this->saturation = validate_uint16('Saturation', $saturation);
        $this->brightness = validate_uint16('Brightness', $brightness);
        $this->temperature = validate_int_range('Temperature', $temperature, 2500, 9500);
    }

    public function getHue(): int
    {
        return $this->hue;
    }

    public function getSaturation(): int
    {
        return $this->saturation;
    }

    public function getBrightness(): int
    {
        return $this->brightness;
    }

    public function getTemperature(): int
    {
        return $this->temperature;
    }
}
