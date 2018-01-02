<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class Service
{
    private $typeId;
    private $port;

    /**
     * @param int $typeId
     * @throws InvalidValueException
     */
    private function setTypeId(int $typeId): void
    {
        if ($typeId < 0 || $typeId > 255) {
            throw new InvalidValueException("Service type ID {$typeId} outside allowable range of 0 - 255");
        }

        $this->typeId = $typeId;
    }

    /**
     * @param int $port
     * @throws InvalidValueException
     */
    private function setPort(int $port): void
    {
        // Protocol spec states this is a uint32 rather than a uint16, so allow any uint32 value at the protocol level
        if ($port < UINT32_MIN || $port > UINT32_MAX) {
            throw new InvalidValueException(
                "Port {$port} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->port = $port;
    }

    /**
     * @param int $typeId
     * @param int $port
     * @throws InvalidValueException
     */
    public function __construct(int $typeId, int $port)
    {
        $this->setTypeId($typeId);
        $this->setPort($port);
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getName(): string
    {
        try {
            return ServiceTypes::parseValue($this->typeId);
        } catch (\InvalidArgumentException $e) {
            return "Unknown({$this->typeId})";
        }
    }
}
