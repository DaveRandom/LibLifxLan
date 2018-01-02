<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

final class ProtocolHeader
{
    public const WIRE_SIZE = 12;

    private $type;

    /**
     * @param int $type
     * @throws InvalidValueException
     */
    private function setType(int $type): void
    {
        if ($type < 0 || $type > 65535) {
            throw new InvalidValueException("Message type {$type} outside allowable range of 0 - 65535");
        }

        $this->type = $type;
    }

    /**
     * @param int $type
     * @throws InvalidValueException
     */
    public function __construct(int $type)
    {
        $this->setType($type);
    }

    public function getType(): int
    {
        return $this->type;
    }
}
