<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;
use function DaveRandom\LibLifxLan\validate_uint32;

abstract class Firmware
{
    private $build;
    private $version;

    /**
     * @param \DateTimeInterface $build
     * @param int $version
     * @throws InvalidValueException
     */
    protected function __construct(\DateTimeInterface $build, int $version)
    {
        $this->build = datetimeinterface_to_datetimeimmutable($build);
        $this->version = validate_uint32('Firmware version', $version);
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
