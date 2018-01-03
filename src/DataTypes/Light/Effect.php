<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class Effect
{
    public const SET_HUE         = 0b0001;
    public const SET_SATURATION  = 0b0010;
    public const SET_BRIGHTNESS  = 0b0100;
    public const SET_TEMPERATURE = 0b1000;
    public const SET_ALL = self::SET_HUE | self::SET_SATURATION | self::SET_BRIGHTNESS | self::SET_TEMPERATURE;

    private $transient;
    private $color;
    private $period;
    private $cycles;
    private $skewRatio;
    private $waveform;
    private $options;

    private function setTransient(bool $transient): void
    {
        $this->transient = $transient;
    }

    private function setColor(HsbkColor $color): void
    {
        $this->color = $color;
    }

    /**
     * @param int $period
     * @throws InvalidValueException
     */
    private function setPeriod(int $period): void
    {
        if ($period < UINT32_MIN || $period > UINT32_MAX) {
            throw new InvalidValueException(
                "Period {$period} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->period = $period;
    }

    private function setCycles(float $cycles): void
    {
        $this->cycles = $cycles;
    }

    /**
     * @param int $skewRatio
     * @throws InvalidValueException
     */
    private function setSkewRatio(int $skewRatio): void
    {
        if ($skewRatio < -32768 || $skewRatio > 32767) {
            throw new InvalidValueException("Skew ratio {$skewRatio} outside allowable range of -32768 - 32767");
        }

        $this->skewRatio = $skewRatio;
    }

    /**
     * @param int $waveform
     * @throws InvalidValueException
     */
    private function setWaveform(int $waveform): void
    {
        if ($waveform < 0 || $waveform > 255) {
            throw new InvalidValueException("Waveform {$waveform} outside allowable range of 0 - 255");
        }

        $this->waveform = $waveform;
    }

    private function setOptions(int $options): void
    {
        $this->options = $options;
    }

    /**
     * @param bool $transient
     * @param HsbkColor $color
     * @param int $period
     * @param float $cycles
     * @param int $skewRatio
     * @param int $waveform
     * @param int $options
     * @throws InvalidValueException
     */
    public function __construct(
        bool $transient,
        HsbkColor $color,
        int $period,
        float $cycles,
        int $skewRatio,
        int $waveform,
        int $options = self::SET_ALL
    ) {
        $this->setTransient($transient);
        $this->setColor($color);
        $this->setPeriod($period);
        $this->setCycles($cycles);
        $this->setSkewRatio($skewRatio);
        $this->setWaveform($waveform);
        $this->setOptions($options);
    }

    public function isTransient(): bool
    {
        return $this->transient;
    }

    public function getColor(): HsbkColor
    {
        return $this->color;
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    public function getCycles(): float
    {
        return $this->cycles;
    }

    public function getSkewRatio(): int
    {
        return $this->skewRatio;
    }

    public function getWaveform(): int
    {
        return $this->waveform;
    }

    public function getOptions(): int
    {
        return $this->options;
    }
}
