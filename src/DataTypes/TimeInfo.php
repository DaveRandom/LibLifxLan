<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

final class TimeInfo
{
    private $time;
    private $uptime;
    private $downtime;

    /**
     * @param \DateTimeInterface $time
     * @param int $uptime
     * @param int $downtime
     */
    public function __construct(\DateTimeInterface $time, int $uptime, int $downtime)
    {
        $this->time = datetimeinterface_to_datetimeimmutable($time);
        $this->uptime = $uptime;
        $this->downtime = $downtime;
    }

    public function getTime(): \DateTimeImmutable
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
