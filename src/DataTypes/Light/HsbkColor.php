<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

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
        if ($hue < 0 || $hue > 65535) {
            throw new InvalidValueException("Value '{$hue}' for hue outside range of allowable values 0 - 65535");
        }

        if ($saturation < 0 || $saturation > 65535) {
            throw new InvalidValueException("Value '{$saturation}' for saturation outside range of allowable values 0 - 65535");
        }

        if ($brightness < 0 || $brightness > 65535) {
            throw new InvalidValueException("Value '{$brightness}' for brightness outside range of allowable values 0 - 65535");
        }

        if ($temperature < 2500 || $temperature > 9500) {
            throw new InvalidValueException("Value '{$temperature}' for temperature outside range of allowable values 2500 - 9500");
        }

        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->brightness = $brightness;
        $this->temperature = $temperature;
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
