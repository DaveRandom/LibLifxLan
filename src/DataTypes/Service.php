<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_uint32;
use function DaveRandom\LibLifxLan\validate_uint8;

final class Service
{
    private $typeId;
    private $port;

    /**
     * @param int $typeId
     * @param int $port
     * @throws InvalidValueException
     */
    public function __construct(int $typeId, int $port)
    {
        $this->typeId = validate_uint8('Service type ID', $typeId);
        $this->port = validate_uint32('Port', $port);
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
