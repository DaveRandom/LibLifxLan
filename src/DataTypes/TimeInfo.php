<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

final class TimeInfo
{
    private $time;
    private $uptime;
    private $downtime;

    private function setTime(\DateTimeImmutable $time): void
    {
        $this->time = $time;
    }

    private function setUptime(int $uptime): void
    {
        $this->uptime = $uptime;
    }

    private function setDowntime(int $downtime): void
    {
        $this->downtime = $downtime;
    }

    public function __construct(\DateTimeInterface $time, int $uptime, int $downtime)
    {
        $this->setTime(datetimeinterface_to_datetimeimmutable($time));
        $this->setUptime($uptime);
        $this->setDowntime($downtime);
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
