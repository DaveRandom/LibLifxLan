<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Responses;

use DaveRandom\LibLifxLan\Messages\ResponseMessage;

final class StatePower extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 118;
    public const WIRE_SIZE = 2;

    private $level;

    public function __construct(int $level)
    {
        parent::__construct();

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
