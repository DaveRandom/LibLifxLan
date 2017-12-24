<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes;

final class WifiFirmware
{
    private $build;
    private $reserved;
    private $version;

    public function __construct(int $build, int $reserved, int $version)
    {
        $this->build = $build;
        $this->reserved = $reserved;
        $this->version = $version;
    }

    public function getBuild(): int
    {
        return $this->build;
    }

    public function getReserved(): int
    {
        return $this->reserved;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
