<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

abstract class Firmware
{
    private $build;
    private $version;

    protected function __construct(\DateTimeInterface $build, int $version)
    {
        $this->build = datetimeinterface_to_datetimeimmutable($build);
        $this->version = $version;
    }

    public function getBuild(): \DateTimeImmutable
    {
        return $this->build;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
