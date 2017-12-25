<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

final class PowerTransition
{
    private $level;
    private $duration;

    public function __construct(int $level, int $duration)
    {
        $this->level = $level;
        $this->duration = $duration;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }
}
