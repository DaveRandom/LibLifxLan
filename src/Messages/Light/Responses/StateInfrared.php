<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Responses;

use DaveRandom\LibLifxLan\Messages\ResponseMessage;

final class StateInfrared extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 122;
    public const WIRE_SIZE = 2;

    private $brightness;

    public function __construct(int $brightness)
    {
        parent::__construct();

        $this->brightness = $brightness;
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
