<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Responses;

use DaveRandom\LifxLan\Messages\Message;

final class StatePower extends Message
{
    public const MESSAGE_TYPE_ID = 118;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }

    // todo
}
