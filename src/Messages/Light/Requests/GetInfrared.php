<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Requests;

use DaveRandom\LifxLan\Messages\RequestMessage;

final class GetInfrared extends RequestMessage
{
    public const MESSAGE_TYPE_ID = 120;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }

    // todo
}
