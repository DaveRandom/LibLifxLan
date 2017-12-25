<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

final class State
{
    private $color;
    private $power;
    private $label;

    public function __construct(HsbkColor $color, int $power, string $label)
    {
        $this->color = $color;
        $this->power = $power;
        $this->label = $label;
    }

    public function getColor(): HsbkColor
    {
        return $this->color;
    }

    public function getPower(): int
    {
        return $this->power;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
