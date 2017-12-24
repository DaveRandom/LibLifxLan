<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes;

final class Info
{
    private $time;
    private $uptime;
    private $downtime;

    public function __construct(int $time, int $uptime, int $downtime)
    {
        $this->time = $time;
        $this->uptime = $uptime;
        $this->downtime = $downtime;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getUptime(): int
    {
        return $this->uptime;
    }

    public function getDowntime(): int
    {
        return $this->downtime;
    }
}
