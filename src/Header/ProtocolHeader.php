<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_uint16;

final class ProtocolHeader
{
    public const WIRE_SIZE = 12;

    private $type;

    /**
     * @param int $type
     * @throws InvalidValueException
     */
    public function __construct(int $type)
    {
        $this->type = validate_uint16('Message type', $type);
    }

    public function getType(): int
    {
        return $this->type;
    }
}
