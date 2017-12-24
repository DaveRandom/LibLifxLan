<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes;

final class HostInfo
{
    private $signal;
    private $tx;
    private $rx;
    private $reservedBits;

    public function __construct(float $signal, int $tx, int $rx, int $reservedBits)
    {
        $this->signal = $signal;
        $this->tx = $tx;
        $this->rx = $rx;
        $this->reservedBits = $reservedBits;
    }

    public function getSignal(): float
    {
        return $this->signal;
    }

    public function getTx(): int
    {
        return $this->tx;
    }

    public function getRx(): int
    {
        return $this->rx;
    }

    public function getReservedBits(): int
    {
        return $this->reservedBits;
    }
}
