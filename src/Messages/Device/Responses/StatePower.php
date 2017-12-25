<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StatePower extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 22;
    public const PAYLOAD_SIZE = 2;

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
}
