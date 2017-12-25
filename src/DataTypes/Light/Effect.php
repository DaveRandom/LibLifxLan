<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes\Light;

final class Effect
{
    public const SET_HUE        = 0b0001;
    public const SET_SATURATION = 0b0010;
    public const SET_BRIGHTNESS = 0b0100;
    public const SET_TEMPERATURE     = 0b1000;
    public const SET_ALL = self::SET_HUE | self::SET_SATURATION | self::SET_BRIGHTNESS | self::SET_TEMPERATURE;

    private $transient;
    private $color;
    private $period;
    private $cycles;
    private $skewRatio;
    private $waveform;
    private $options;

    public function __construct(bool $transient, HsbkColor $color, int $period, float $cycles, int $skewRatio, int $waveform, int $options = self::SET_ALL)
    {
        $this->transient = $transient;
        $this->color = $color;
        $this->period = $period;
        $this->cycles = $cycles;
        $this->skewRatio = $skewRatio;
        $this->waveform = $waveform;
        $this->options = $options;
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
