<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes;

final class Service
{
    private $typeId;
    private $port;

    public function __construct(int $typeId, int $port)
    {
        $this->typeId = $typeId;
        $this->port = $port;
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
