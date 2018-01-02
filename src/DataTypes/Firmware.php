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

    private function setBuild(\DateTimeImmutable $build): void
    {
        $this->build = $build;
    }

    /**
     * @param int $version
     * @throws InvalidValueException
     */
    private function setVersion(int $version): void
    {
        if ($version < UINT32_MIN || $version > UINT32_MAX) {
            throw new InvalidValueException(
                "Firmware version {$version} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->version = $version;
    }

    /**
     * @param \DateTimeInterface $build
     * @param int $version
     * @throws InvalidValueException
     */
    protected function __construct(\DateTimeInterface $build, int $version)
    {
        $this->setBuild(datetimeinterface_to_datetimeimmutable($build));
        $this->setVersion($version);
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
