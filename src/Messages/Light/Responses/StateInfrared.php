<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Responses;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use DaveRandom\LibLifxLan\Messages\Message;
use function DaveRandom\LibLifxLan\validate_uint16;

final class StateInfrared implements Message
{
    public const MESSAGE_TYPE_ID = 122;
    public const WIRE_SIZE = 2;

    private $brightness;

    /**
     * @param int $brightness
     * @throws InvalidValueException
     */
    public function __construct(int $brightness)
    {
        $this->brightness = validate_uint16('Brightness', $brightness);
    }

    public function getBrightness(): int
    {
        return $this->brightness;
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
