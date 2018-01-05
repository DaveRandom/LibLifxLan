<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Commands;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use DaveRandom\LibLifxLan\Messages\Message;
use function DaveRandom\LibLifxLan\validate_uint16;

final class SetPower implements Message
{
    public const MESSAGE_TYPE_ID = 21;
    public const WIRE_SIZE = 2;

    private $level;

    /**
     * @param int $level
     * @throws InvalidValueException
     */
    public function __construct(int $level)
    {
        $this->level = validate_uint16('Power level', $level);
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }

    public function getWireSize(): int
    {
        return self::WIRE_SIZE;
    }
}
