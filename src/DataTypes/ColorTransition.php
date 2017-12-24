<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes;

use DaveRandom\LifxLan\HsbkColor;

final class ColorTransition
{
    private $color;
    private $duration;

    public function __construct(HsbkColor $color, int $duration)
    {
        $this->color = $color;
        $this->duration = $duration;
    }

    public function getColor(): HsbkColor
    {
        return $this->color;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }
}
