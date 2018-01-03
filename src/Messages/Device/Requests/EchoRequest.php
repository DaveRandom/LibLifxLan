<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use DaveRandom\LibLifxLan\Messages\Message;

final class EchoRequest implements Message
{
    public const MESSAGE_TYPE_ID = 58;
    public const WIRE_SIZE = 64;

    private $payload;

    /**
     * @param string $payload
     * @throws InvalidValueException
     */
    public function __construct(string $payload)
    {
        if (\strlen($payload) !== 64) {
            throw new InvalidValueException("Echo request payload should be exactly 64 bytes");
        }

        $this->payload = $payload;
    }

    public function getPayload(): string
    {
        return $this->payload;
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
