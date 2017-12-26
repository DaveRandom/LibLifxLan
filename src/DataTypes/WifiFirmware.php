<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

final class WifiFirmware extends Firmware
{
    public function __construct(\DateTimeInterface $build, int $version)
    {
        parent::__construct($build, $version);
    }
}
