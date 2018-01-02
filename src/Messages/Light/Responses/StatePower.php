<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Responses;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use DaveRandom\LibLifxLan\Messages\Message;

final class StatePower implements Message
{
    public const MESSAGE_TYPE_ID = 118;
    public const WIRE_SIZE = 2;

    private $level;

    /**
     * @param int $level
     * @throws InvalidValueException
     */
    public function __construct(int $level)
    {
        if ($level < 0 || $level > 65535) {
            throw new InvalidValueException("Power level {$level} outside allowable range of 0 - 65535");
        }

        $this->level = $level;
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
