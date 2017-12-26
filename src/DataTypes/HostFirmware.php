<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

final class HostFirmware extends Firmware
{
    public function __construct(\DateTimeInterface $build, int $version)
    {
        parent::__construct($build, $version);
    }
}
