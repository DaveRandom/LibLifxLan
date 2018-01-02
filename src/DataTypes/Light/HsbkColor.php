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
     * @throws InvalidValueException
     */
    private function setHue(int $hue): void
    {
        if ($hue < 0 || $hue > 65535) {
            throw new InvalidValueException("Value '{$hue}' for hue outside range of allowable values 0 - 65535");
        }

        $this->hue = $hue;
    }

    /**
     * @param int $saturation
     * @throws InvalidValueException
     */
    private function setSaturation(int $saturation): void
    {
        if ($saturation < 0 || $saturation > 65535) {
            throw new InvalidValueException(
                "Value '{$saturation}' for saturation outside range of allowable values 0 - 65535"
            );
        }

        $this->saturation = $saturation;
    }

    /**
     * @param int $brightness
     * @throws InvalidValueException
     */
    private function setBrightness(int $brightness): void
    {
        if ($brightness < 0 || $brightness > 65535) {
            throw new InvalidValueException(
                "Value '{$brightness}' for brightness outside range of allowable values 0 - 65535"
            );
        }

        $this->brightness = $brightness;
    }

    /**
     * @param int $temperature
     * @throws InvalidValueException
     */
    private function setTemperature(int $temperature): void
    {
        if ($temperature < 2500 || $temperature > 9500) {
            throw new InvalidValueException(
                "Value '{$temperature}' for temperature outside range of allowable values 2500 - 9500"
            );
        }

        $this->temperature = $temperature;
    }

    /**
     * @param int $hue
     * @param int $saturation
     * @param int $brightness
     * @param int $temperature
     * @throws InvalidValueException
     */
    public function __construct(int $hue, int $saturation, int $brightness, int $temperature)
    {
        $this->setHue($hue);
        $this->setSaturation($saturation);
        $this->setBrightness($brightness);
        $this->setTemperature($temperature);
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
