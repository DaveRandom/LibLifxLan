<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

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
        if ($version < UINT32_MIN || $version > UINT32_MAX) {
            throw new InvalidValueException(
                "Firmware version {$version} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

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
