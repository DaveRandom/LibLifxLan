<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

final class WifiFirmware extends Firmware
{
    /**
     * @param \DateTimeInterface $build
     * @param int $version
     * @throws InvalidValueException
     */
    public function __construct(\DateTimeInterface $build, int $version)
    {
        parent::__construct($build, $version);
    }
}
