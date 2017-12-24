<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Responses;

use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateInfrared extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 122;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }

    // todo
}
